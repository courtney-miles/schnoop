<?php

namespace MilesAsylum\Schnoop\Inspector;

class MySQLInspector implements InspectorInterface
{
    /**
     * @var \PDO
     */
    protected $pdo;

    /**
     * @var \PDOStatement
     */
    protected $stmtSelectDatabaseNames;

    /**
     * @var string
     */
    protected $querySelectTableNames;

    /**
     * @var \PDOStatement
     */
    protected $stmtSelectFunctionNames;

    /**
     * @var \PDOStatement
     */
    protected $stmtSelectProcedureNames;

    /**
     * @var string
     */
    protected $querySelectTriggerNamesForDatabase;

    /**
     * @var string
     */
    protected $querySelectTriggerNamesForTable;

    /**
     * @var \PDOStatement
     */
    protected $stmtSelectActiveDatabase;

    /**
     * MySQLInspector constructor.
     * @param \PDO $pdo
     */
    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;

        $this->stmtSelectDatabaseNames = $this->pdo->prepare(<<<SQL
SHOW DATABASES;
SQL
        );

        $this->querySelectTableNames = <<<SQL
SHOW TABLES FROM `%s`;
SQL;

        $this->stmtSelectFunctionNames = $this->pdo->prepare(<<<SQL
SHOW FUNCTION STATUS WHERE Db = :databaseName
SQL
        );

        $this->stmtSelectProcedureNames = $this->pdo->prepare(<<<SQL
SHOW PROCEDURE STATUS WHERE Db = :databaseName
SQL
        );

        $this->querySelectTriggerNamesForTable = <<<SQL
SHOW TRIGGERS FROM `%s` WHERE `Table` = :tableName
SQL;

        $this->stmtSelectActiveDatabase = $this->pdo->prepare(<<<SQL
SELECT DATABASE()
SQL
        );
    }

    /**
     * {@inheritdoc}
     */
    public function fetchDatabaseList()
    {
        $this->stmtSelectDatabaseNames->execute();

        return $this->stmtSelectDatabaseNames->fetchAll(\PDO::FETCH_COLUMN);
    }

    /**
     * {@inheritdoc}
     */
    public function fetchTableList($databaseName)
    {
        $stmt = $this->pdo->query(
            sprintf(
                $this->querySelectTableNames,
                $databaseName
            )
        );

        return $stmt->fetchAll(\PDO::FETCH_COLUMN);
    }

    /**
     * {@inheritdoc}
     */
    public function fetchActiveDatabase()
    {
        $this->stmtSelectActiveDatabase->execute();

        return $this->stmtSelectActiveDatabase->fetchColumn();
    }

    /**
     * {@inheritdoc}
     */
    public function fetchFunctionList($databaseName)
    {
        $this->stmtSelectFunctionNames->execute([':databaseName' => $databaseName]);

        return $this->stmtSelectFunctionNames->fetchAll(\PDO::FETCH_COLUMN, 1);
    }

    /**
     * {@inheritdoc}
     */
    public function fetchProcedureList($databaseName)
    {
        $this->stmtSelectProcedureNames->execute([':databaseName' => $databaseName]);

        return $this->stmtSelectProcedureNames->fetchAll(\PDO::FETCH_COLUMN, 1);
    }

    /**
     * {@inheritdoc}
     */
    public function fetchTriggerList($databaseName, $tableName)
    {
        $query = sprintf(
            $this->querySelectTriggerNamesForTable,
            $databaseName
        );

        $stmt = $this->pdo->prepare($query);
        $stmt->execute([':tableName' => $tableName]);

        return $stmt->fetchAll(\PDO::FETCH_COLUMN);
    }

    /**
     * {@inheritdoc}
     */
    public function getPDO()
    {
        return $this->pdo;
    }
}
