<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 2/06/16
 * Time: 5:06 PM
 */

namespace MilesAsylum\Schnoop\DbInspector;

use PDO;

class MySQLInspector implements DbInspectorInterface
{
    /**
     * @var PDO
     */
    protected $pdo;

    protected $sqlShowTableStatus;

    protected $sqlShowFullColumns;

    protected $sqlSelectDatabaseAttributes;

    protected $sqlShowDatabases;

    protected $sqlShowTables;

    protected $sqlShowIndexes;

    protected $sqlShowTriggers;

    protected $sqlShowFunctions;

    protected $sqlShowFunction;

    protected $sqlShowFunctionCreate;

    protected $sqlShowProcedures;

    protected $sqlShowProcedure;

    protected $sqlShowProcedureCreate;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;

        $this->sqlShowDatabases = <<< SQL
SHOW DATABASES;
SQL;
        $this->sqlSelectDatabaseAttributes = <<< SQL
SELECT DATABASE() AS `name`, @@character_set_database AS `character_set_database`, @@collation_database AS `collation_database`
SQL;
        $this->sqlShowTables = <<< SQL
        SHOW TABLES FROM `%s`
SQL;
        $this->sqlShowTableStatus = <<< SQL
SHOW TABLE STATUS FROM `%s` WHERE `Name` = :table AND `Engine` IS NOT NULL 
SQL;
        $this->sqlShowFullColumns = <<< SQL
SHOW FULL COLUMNS FROM `%s`.`%s`
SQL;
        $this->sqlShowIndexes = <<< SQL
SHOW INDEXES FROM `%s`.`%s`
SQL;
        $this->sqlShowTriggers = <<< SQL
SHOW TRIGGERS IN `%s` LIKE :table
SQL;
        $this->sqlShowFunctions = <<< SQL
SHOW FUNCTION STATUS WHERE `Db` = :database
SQL;
        $this->sqlShowFunction = <<< SQL
SHOW FUNCTION STATUS WHERE `Db` = :database AND `Name` = :function
SQL;
        $this->sqlShowFunctionCreate = <<< SQL
SHOW CREATE FUNCTION `%s`.`%s` 
SQL;
        $this->sqlShowProcedures = <<< SQL
SHOW PROCEDURE STATUS WHERE `Db` = :database
SQL;
        $this->sqlShowProcedure = <<< SQL
SHOW PROCEDURE STATUS WHERE `Db` = :database AND `Name` = :procedure
SQL;
        $this->sqlShowProcedureCreate = <<< SQL
SHOW CREATE PROCEDURE `%s`.`%s`
SQL;

    }

    public function fetchDatabaseList()
    {
        $databaseList = $this->pdo->query($this->sqlShowDatabases)->fetchAll(PDO::FETCH_COLUMN);

        return $databaseList;
    }

    public function fetchDatabase($databaseName)
    {
        $prevDatabase = $this->pdo->query('SELECT DATABASE()')->fetchColumn();

        $this->pdo->query("USE `$databaseName`");
        $row = $this->pdo
            ->query($this->sqlSelectDatabaseAttributes)
            ->fetch(PDO::FETCH_ASSOC);

        if (!empty($prevDatabase)) {
            $this->pdo->query("USE `$prevDatabase`");
        }
        
        $rawDatabase = $this->formatResultRow($row);

        return $rawDatabase;
    }

    public function fetchTableList($databaseName)
    {
        $tableList = $this->pdo->query(
            sprintf(
                $this->sqlShowTables,
                $databaseName
            )
        )->fetchAll(PDO::FETCH_COLUMN);


        return $tableList;
    }

    public function fetchTable($databaseName, $tableName)
    {
        $filterFields = [
            'name',
            'engine',
            'row_format',
            'collation',
            'comment'
        ];

        $stmt = $this->pdo->prepare(
            sprintf(
                $this->sqlShowTableStatus,
                $databaseName
            )
        );

        $stmt->execute([':table' => $tableName]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $rawTable = $this->formatResultRow($row, $filterFields);

        return $rawTable;
    }

    public function fetchColumns($databaseName, $tableName)
    {
        $filterFields = [
            'field',
            'type',
            'collation',
            'null',
            'default',
            'extra',
            'comment'
        ];

        $rows = $this->pdo->query(
            sprintf(
                $this->sqlShowFullColumns,
                $databaseName,
                $tableName
            )
        )->fetchAll(PDO::FETCH_ASSOC);

        $rawColumns = $this->formatResultRows($rows, $filterFields);

        return $rawColumns;
    }

    public function fetchIndexes($databaseName, $tableName)
    {
        $filterFields = [
            'key_name',
            'non_unique',
            'seq_in_index',
            'column_name',
            'collation',
            'index_type',
            'index_comment'
        ];

        $rows = $this->pdo->query(
            sprintf(
                $this->sqlShowIndexes,
                $databaseName,
                $tableName
            )
        )->fetchAll(PDO::FETCH_ASSOC);

        $rawIndexes = $this->formatResultRows($rows, $filterFields);

        return $rawIndexes;
    }

    public function fetchTriggers($databaseName, $tableName)
    {
        $includeFields = [
            'trigger',
            'event',
            'timing',
            'sql_mode',
            'definer',
            'statement'
        ];

        $stmtShowTriggers = $this->pdo->prepare(
            sprintf(
                $this->sqlShowTriggers,
                $databaseName
            )
        );

        $stmtShowTriggers->execute([':table' => $tableName]);

        $rows = $stmtShowTriggers->fetchAll(PDO::FETCH_ASSOC);

        $rawTriggers = $this->formatResultRows($rows, $includeFields);

        return $rawTriggers;
    }

    public function fetchFunctionList($databaseName)
    {
        $stmtShowFunctions = $this->pdo->prepare($this->sqlShowFunctions);
        $stmtShowFunctions->execute([':database' => $databaseName]);

        return $stmtShowFunctions->fetchAll(PDO::FETCH_COLUMN, 1);
    }

    public function fetchFunction($database, $functionName)
    {
        $includeFields = [
            'name',
            'definer',
            'sql_mode',
            'comment',
            'security',
            'create_function'
        ];

        $stmtShowFunction = $this->pdo->prepare($this->sqlShowFunction);
        $stmtShowFunction->execute([':database' => $database, ':function' => $functionName]);
        $row = $stmtShowFunction->fetch(PDO::FETCH_ASSOC);

        $row = array_merge(
            $row,
            $this->pdo->query(
                sprintf(
                    $this->sqlShowFunctionCreate,
                    $database,
                    $functionName
                )
            )->fetch(PDO::FETCH_ASSOC)
        );

        $rawFunction = $this->formatResultRow($row, $includeFields);

        return $rawFunction;
    }

    public function fetchProcedureList($databaseName)
    {
        $stmtShowProcdures = $this->pdo->prepare($this->sqlShowProcedures);
        $stmtShowProcdures->execute([':database' => $databaseName]);

        return $stmtShowProcdures->fetchAll(PDO::FETCH_COLUMN, 1);
    }

    public function fetchProcedure($databaseName, $procedureName)
    {
        $includeFields = [
            'name',
            'definer',
            'sql_mode',
            'comment',
            'security',
            'create_procedure'
        ];
        
        $stmtShowProcedure = $this->pdo->prepare($this->sqlShowProcedure);
        $stmtShowProcedure->execute([':database' => $databaseName, ':procedure' => $procedureName]);
        $row = $stmtShowProcedure->fetch(PDO::FETCH_ASSOC);

        $row = array_merge(
            $row,
            $this->pdo->query(
                sprintf(
                    $this->sqlShowProcedureCreate,
                    $databaseName,
                    $procedureName
                )
            )->fetch(PDO::FETCH_ASSOC)
        );

        $rawProcedure = $this->formatResultRow($row, $includeFields);

        return $rawProcedure;
    }

    /**
     * Standadises the format of an array of rows.
     * @param array[] $rows The array of rows to format.
     * @param array|null $filterFields If specified, any fields not in this array will be removed from the row array.
     * @return array
     */
    protected function formatResultRows(array $rows, array $filterFields = null)
    {
        foreach ($rows as $k => $row) {
            $rows[$k] = $this->formatResultRow($row, $filterFields);
        }

        return $rows;
    }

    /**
     * Standardises the format of the row array.
     * @param array $row The row array to format.
     * @param array|null $filterFields If specified, any fields not in this array will be removed from the row array.
     * @return array
     */
    protected function formatResultRow(array $row, array $filterFields = null)
    {
        $keys = array_keys($row);
        array_walk($keys, function (&$value, $key) {
            $value = strtolower(str_replace(' ', '_', $value));
        });

        $row = array_combine($keys, array_values($row));

        if (isset($filterFields)) {
            $row = array_intersect_key($row, array_flip($filterFields));
        }

        return $row;
    }
}