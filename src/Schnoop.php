<?php

namespace MilesAsylum\Schnoop;

use MilesAsylum\Schnoop\Exception\SchnoopException;
use MilesAsylum\Schnoop\Inspector\InspectorInterface;
use MilesAsylum\Schnoop\Inspector\MySQLInspector;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\Column\ColumnFactory;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\Constraint\ForeignKeyFactory;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\Constraint\IndexFactory;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\Database\DatabaseFactory;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType\DataTypeFactory;
use MilesAsylum\Schnoop\SchemaAdapter\MySQL\Database;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\Routine\FunctionFactory;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\Routine\ParametersFactory;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\Routine\ParametersLexer;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\Routine\ParametersParser;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\Routine\ProcedureFactory;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\SetVar\SqlModeFactory;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\Trigger\TriggerFactory;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\SchemaBuilder;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\Table\TableFactory;
use PDO;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\SchemaBuilderInterface;

class Schnoop
{
    /**
     * @var InspectorInterface
     */
    protected $dbInspector;

    /**
     * @var SchemaBuilderInterface
     */
    protected $dbBuilder;

    /**
     * @var Database[]
     */
    protected $loadedDatabase = [];

    /**
     * Schnoop constructor.
     * @param InspectorInterface $dbInspector
     * @param SchemaBuilderInterface $dbBuilder
     */
    public function __construct(
        InspectorInterface $dbInspector,
        SchemaBuilderInterface $dbBuilder
    ) {
        $this->dbInspector = $dbInspector;
        $this->dbBuilder = $dbBuilder;
        $this->dbBuilder->setSchnoop($this);
    }

    public function getDatabaseList()
    {
        return $this->dbInspector->fetchDatabaseList();
    }

    /**
     * @param $databaseName
     * @return bool
     */
    public function hasDatabase($databaseName)
    {
        return in_array($databaseName, $this->dbInspector->fetchDatabaseList());
    }

    /**
     * @param string|null $databaseName
     * @return Database
     * @throws SchnoopException
     */
    public function getDatabase($databaseName = null)
    {
        if ($databaseName === null) {
            $databaseName = $this->ensureFetchActiveDatabaseName();
        }

        if (!isset($this->loadedDatabase[$databaseName])) {
            if ($this->hasDatabase($databaseName)) {
                $this->loadedDatabase[$databaseName] = $this->dbBuilder->fetchDatabase($databaseName);
            }
        }

        return isset($this->loadedDatabase[$databaseName]) ? $this->loadedDatabase[$databaseName] : null;
    }

    public function getTableList($databaseName = null)
    {
        $tableList = null;

        $databaseName = $this->ensureResolveDatabaseName($databaseName);

        $tableList = $this->dbInspector->fetchTableList($databaseName);

        return $this->dbInspector->fetchTableList($databaseName);
    }

    public function getTable($tableName, $databaseName = null)
    {
        $databaseName = $this->ensureResolveDatabaseName($databaseName);

        return $this->dbBuilder->fetchTable($tableName, $databaseName);
    }

    public function hasTable($tableName, $databaseName = null)
    {
        $databaseName = $this->ensureResolveDatabaseName($databaseName);

        return in_array($tableName, $this->dbInspector->fetchTableList($databaseName));
    }

    public function hasTriggers($tableName, $databaseName = null)
    {
        $databaseName = $this->ensureResolveDatabaseName($databaseName);

        $this->ensureTableExists($tableName, $databaseName);

        return (bool)count($this->dbInspector->fetchTriggerList($databaseName, $tableName));
    }

    public function getTriggers($tableName, $databaseName = null)
    {
        $databaseName = $this->ensureResolveDatabaseName($databaseName);

        $this->ensureTableExists($tableName, $databaseName);

        return $this->dbBuilder->fetchTriggers($tableName, $databaseName);
    }

    public function hasFunction($functionName, $databaseName = null)
    {
        $databaseName = $this->ensureResolveDatabaseName($databaseName);

        return in_array($functionName, $this->dbInspector->fetchFunctionList($databaseName));
    }

    public function getFunction($functionName, $databaseName = null)
    {
        $databaseName = $this->ensureResolveDatabaseName($databaseName);

        return $this->dbBuilder->fetchFunction($functionName, $databaseName);
    }

    public function hasProcedure($procedureName, $databaseName = null)
    {
        $databaseName = $this->ensureResolveDatabaseName($databaseName);

        return in_array($procedureName, $this->dbInspector->fetchProcedureList($databaseName));
    }

    public function getProcedure($procedureName, $databaseName = null)
    {
        $databaseName = $this->ensureResolveDatabaseName($databaseName);

        return $this->dbBuilder->fetchProcedure($procedureName, $databaseName);
    }

    public static function createSelf(PDO $pdo)
    {
        $dataTypeFactory = DataTypeFactory::createSelf();
        $sqlModeFactory = new SqlModeFactory();
        $paramsFactory = new ParametersFactory(
            new ParametersParser(new ParametersLexer()),
            $dataTypeFactory
        );

        return new self(
            new MySQLInspector(
                $pdo
            ),
            new SchemaBuilder(
                new DatabaseFactory($pdo),
                new TableFactory($pdo),
                new ColumnFactory($pdo, $dataTypeFactory),
                new IndexFactory($pdo),
                new ForeignKeyFactory($pdo),
                new TriggerFactory($pdo, $sqlModeFactory),
                new FunctionFactory($pdo, $paramsFactory, $sqlModeFactory, $dataTypeFactory),
                new ProcedureFactory($pdo, $paramsFactory, $sqlModeFactory)
            )
        );
    }

    protected function ensureResolveDatabaseName($databaseName = null)
    {
        if ($databaseName === null) {
            $databaseName = $this->ensureFetchActiveDatabaseName();
        } else {
            $this->ensureDatabaseExists($databaseName);
        }

        return $databaseName;
    }

    protected function ensureFetchActiveDatabaseName()
    {
        $databaseName = $this->dbInspector->fetchActiveDatabase();

        if (empty($databaseName)) {
            throw new SchnoopException('Database not specified and an active database has not been set.');
        }

        return $databaseName;
    }

    protected function ensureDatabaseExists($databaseName)
    {
        if (!$this->hasDatabase($databaseName)) {
            throw new SchnoopException("A database named '$databaseName' does not exist.");
        }
    }

    protected function ensureTableExists($tableName, $databaseName)
    {
        if (!$this->hasTable($tableName, $databaseName)) {
            throw new SchnoopException("A table named '$tableName' does not exist in database '$databaseName'.");
        }
    }
}
