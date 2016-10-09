<?php

namespace MilesAsylum\Schnoop;

use MilesAsylum\Schnoop\Inspector\InspectorInterface;
use MilesAsylum\Schnoop\Inspector\MySQLInspector;
use MilesAsylum\Schnoop\SchemaAdapter\MySQL\Table;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\Column\ColumnFactory;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\Constraint\ForeignKeyFactory;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\Constraint\IndexFactory;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\Database\DatabaseFactory;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType\DataTypeFactory;
use MilesAsylum\Schnoop\SchemaAdapter\MySQL\Database;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\SetVar\SqlModeFactory;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\Trigger\TriggerFactory;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\SchemaBuilder;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\Table\TableFactory;
use MilesAsylum\SchnoopSchema\MySQL\Database\DatabaseInterface;
use MilesAsylum\SchnoopSchema\MySQL\Table\TableInterface;
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
     * @param null $databaseName
     * @return Database
     */
    public function getDatabase($databaseName = null)
    {
        if ($databaseName === null) {
            $databaseName = $this->dbInspector->fetchActiveDatabase();
        }

        $databaseName = strtolower($databaseName);

        if (!isset($this->loadedDatabase[$databaseName])) {
            $this->loadedDatabase[$databaseName] = $this->dbBuilder->fetchDatabase($databaseName);
        }

        return $this->loadedDatabase[$databaseName];
    }

    public function getTableList($databaseName)
    {
        return $this->dbInspector->fetchTableList($databaseName);
    }

    public function getTable($databaseName, $tableName)
    {
        return $this->dbBuilder->fetchTable($databaseName, $tableName);
    }

    public function hasTable($databaseName, $tableName)
    {
        return in_array($tableName, $this->dbInspector->fetchTableList($databaseName));
    }

    public function getTriggers($databaseName, $tableName)
    {
        return $this->dbBuilder->fetchTriggers($databaseName, $tableName);
    }

    public static function createSelf(PDO $pdo)
    {
        return new self(
            new MySQLInspector(
                $pdo
            ),
            new SchemaBuilder(
                new DatabaseFactory($pdo),
                new TableFactory($pdo),
                new ColumnFactory($pdo, DataTypeFactory::createSelf()),
                new IndexFactory($pdo),
                new ForeignKeyFactory($pdo),
                new TriggerFactory($pdo, new SqlModeFactory())
            )
        );
    }
}
