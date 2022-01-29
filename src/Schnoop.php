<?php

namespace MilesAsylum\Schnoop;

use MilesAsylum\Schnoop\Exception\SchnoopException;
use MilesAsylum\Schnoop\Inspector\InspectorInterface;
use MilesAsylum\Schnoop\Inspector\MySQLInspector;
use MilesAsylum\Schnoop\SchemaAdapter\MySQL\Database;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\Column\ColumnFactory;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\Constraint\ForeignKeyFactory;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\Constraint\IndexFactory;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\Database\DatabaseFactory;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType\DataTypeFactory;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\Routine\FunctionFactory;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\Routine\ParametersFactory;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\Routine\ParametersLexer;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\Routine\ParametersParser;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\Routine\ProcedureFactory;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\SchemaBuilder;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\SchemaBuilderInterface;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\SetVar\SqlModeFactory;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\Table\TableFactory;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\Trigger\TriggerFactory;
use PDO;

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
     */
    public function __construct(
        InspectorInterface $dbInspector,
        SchemaBuilderInterface $dbBuilder
    ) {
        $this->dbInspector = $dbInspector;
        $this->dbBuilder = $dbBuilder;
        $this->dbBuilder->setSchnoop($this);
    }

    /**
     * Get the list of database names on the server.
     *
     * @return array database names
     */
    public function getDatabaseList()
    {
        return $this->dbInspector->fetchDatabaseList();
    }

    /**
     * Check if the named database exists on the server.
     *
     * @param string $databaseName
     *
     * @return bool true if the database exists
     */
    public function hasDatabase($databaseName)
    {
        return in_array($databaseName, $this->dbInspector->fetchDatabaseList());
    }

    /**
     * Get a database from the server.
     *
     * @param string|null $databaseName The database name. Do not supply a name to get the currently active database.
     *
     * @return Database
     *
     * @throws SchnoopException
     */
    public function getDatabase($databaseName = null)
    {
        if (null === $databaseName) {
            $databaseName = $this->ensureFetchActiveDatabaseName();
        }

        if (!isset($this->loadedDatabase[$databaseName])) {
            if ($this->hasDatabase($databaseName)) {
                $this->loadedDatabase[$databaseName] = $this->dbBuilder->fetchDatabase($databaseName);
            }
        }

        return isset($this->loadedDatabase[$databaseName]) ? $this->loadedDatabase[$databaseName] : null;
    }

    /**
     * The the list of table names for the database.
     *
     * @param string|null $databaseName The database name. Do not supply a name to get the currently active database.
     *
     * @return array Table names
     */
    public function getTableList($databaseName = null)
    {
        $tableList = null;

        $databaseName = $this->ensureResolveDatabaseName($databaseName);

        $tableList = $this->dbInspector->fetchTableList($databaseName);

        return $this->dbInspector->fetchTableList($databaseName);
    }

    /**
     * Get a table from the database.
     *
     * @param string      $tableName
     * @param string|null $databaseName The database name. Do not supply a name to get the currently active database.
     *
     * @return SchemaAdapter\MySQL\TableInterface
     */
    public function getTable($tableName, $databaseName = null)
    {
        $databaseName = $this->ensureResolveDatabaseName($databaseName);

        return $this->dbBuilder->fetchTable($tableName, $databaseName);
    }

    /**
     * Check if a table exists in the database.
     *
     * @param string      $tableName
     * @param string|null $databaseName The database name. Do not supply a name to get the currently active database.
     *
     * @return bool true if the table exists
     */
    public function hasTable($tableName, $databaseName = null)
    {
        $databaseName = $this->ensureResolveDatabaseName($databaseName);

        return in_array($tableName, $this->dbInspector->fetchTableList($databaseName));
    }

    /**
     * Check if a table has any triggers.
     *
     * @param string      $tableName
     * @param string|null $databaseName The database name. Do not supply a name to get the currently active database.
     *
     * @return bool true if the table has triggers
     */
    public function hasTriggers($tableName, $databaseName = null)
    {
        $databaseName = $this->ensureResolveDatabaseName($databaseName);

        $this->ensureTableExists($tableName, $databaseName);

        return (bool) count($this->dbInspector->fetchTriggerList($databaseName, $tableName));
    }

    /**
     * Get all the triggers for a table.
     *
     * @param string      $tableName
     * @param string|null $databaseName The database name. Do not supply a name to get the currently active database.
     *
     * @return SchemaAdapter\MySQL\TriggerInterface[]
     */
    public function getTriggers($tableName, $databaseName = null)
    {
        $databaseName = $this->ensureResolveDatabaseName($databaseName);

        $this->ensureTableExists($tableName, $databaseName);

        return $this->dbBuilder->fetchTriggers($tableName, $databaseName);
    }

    /**
     * Check if the function exists in the database.
     *
     * @param string      $functionName
     * @param string|null $databaseName The database name. Do not supply a name to get the currently active database.
     *
     * @return bool true if the function exists
     */
    public function hasFunction($functionName, $databaseName = null)
    {
        $databaseName = $this->ensureResolveDatabaseName($databaseName);

        return in_array($functionName, $this->dbInspector->fetchFunctionList($databaseName));
    }

    /**
     * Get a function from the database.
     *
     * @param string      $functionName
     * @param string|null $databaseName The database name. Do not supply a name to get the currently active database.
     *
     * @return SchemaAdapter\MySQL\RoutineFunctionInterface
     */
    public function getFunction($functionName, $databaseName = null)
    {
        $databaseName = $this->ensureResolveDatabaseName($databaseName);

        return $this->dbBuilder->fetchFunction($functionName, $databaseName);
    }

    /**
     * Check if the named procedure exists in the database.
     *
     * @param string      $procedureName
     * @param string|null $databaseName  The database name. Do not supply a name to get the currently active database.
     *
     * @return bool true if the named procedure exists
     */
    public function hasProcedure($procedureName, $databaseName = null)
    {
        $databaseName = $this->ensureResolveDatabaseName($databaseName);

        return in_array($procedureName, $this->dbInspector->fetchProcedureList($databaseName));
    }

    /**
     * Get the named procedure from the database.
     *
     * @param string      $procedureName
     * @param string|null $databaseName  The database name. Do not supply a name to get the currently active database.
     *
     * @return SchemaAdapter\MySQL\RoutineProcedureInterface
     */
    public function getProcedure($procedureName, $databaseName = null)
    {
        $databaseName = $this->ensureResolveDatabaseName($databaseName);

        return $this->dbBuilder->fetchProcedure($procedureName, $databaseName);
    }

    /**
     * Get the PDO connection used to schnoop the database.
     *
     * @return PDO
     */
    public function getPDO()
    {
        return $this->dbInspector->getPDO();
    }

    /**
     * Factory for constructing this object.
     *
     * @return Schnoop
     */
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

    /**
     * Checks if the current database exists and throw an exception if it does
     * not. If a database is not supplied it will check for an active database
     * and throw an exception if one is not set.
     *
     * @param string|null $databaseName
     *
     * @return string the supplied database name, or the active database if a database name is not supplied
     */
    protected function ensureResolveDatabaseName($databaseName = null)
    {
        if (null === $databaseName) {
            $databaseName = $this->ensureFetchActiveDatabaseName();
        } else {
            $this->ensureDatabaseExists($databaseName);
        }

        return $databaseName;
    }

    /**
     * Fetch the name of the active/selected database, and throw an exception if a database is not selected.
     *
     * @return string name of the active/selected database
     *
     * @throws SchnoopException
     */
    protected function ensureFetchActiveDatabaseName()
    {
        $databaseName = $this->dbInspector->fetchActiveDatabase();

        if (empty($databaseName)) {
            throw new SchnoopException('Database not specified and an active database has not been set.');
        }

        return $databaseName;
    }

    /**
     * Checks if the named database exists on the server and throw an exception if it does not.
     *
     * @param string $databaseName
     *
     * @throws SchnoopException
     */
    protected function ensureDatabaseExists($databaseName)
    {
        if (!$this->hasDatabase($databaseName)) {
            throw new SchnoopException("A database named '$databaseName' does not exist.");
        }
    }

    /**
     * Checks if the named table exists in the database and throw an exception if it does not.
     *
     * @param string $tableName
     * @param string $databaseName
     *
     * @throws SchnoopException
     */
    protected function ensureTableExists($tableName, $databaseName)
    {
        if (!$this->hasTable($tableName, $databaseName)) {
            throw new SchnoopException("A table named '$tableName' does not exist in database '$databaseName'.");
        }
    }
}
