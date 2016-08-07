<?php

namespace MilesAsylum\Schnoop;

use MilesAsylum\Schnoop\DbInspector\DbInspectorInterface;
use MilesAsylum\Schnoop\Exception\SchnoopException;
use MilesAsylum\Schnoop\Schema\DatabaseInterface;
use PDO;
use MilesAsylum\Schnoop\Schema\FactoryInterface;
use MilesAsylum\Schnoop\Schema\TableInterface;

class Schnoop
{
    /**
     * @var PDO
     */
    protected $pdo;

    /**
     * @var DbInspectorInterface
     */
    protected $dbInspector;

    /**
     * @var FactoryInterface
     */
    protected $schemaFactory;

    /**
     * @var string
     */
    protected $activeDatabaseName;

    /**
     * @var array
     */
    protected $databaseList;

    /**
     * Schnoop constructor.
     * @param PDO $pdo
     * @param DbInspectorInterface $dbAdapter
     * @param FactoryInterface $factoryInterface
     */
    public function __construct(
        PDO $pdo,
        DbInspectorInterface $dbAdapter,
        FactoryInterface $factoryInterface
    ) {
        $this->pdo = $pdo;
        $this->dbInspector = $dbAdapter;
        $this->schemaFactory = $factoryInterface;

        $this->loadDatabaseList();

        $activeDatabaseName = $this->dbInspector->fetchActiveDatabase();

        if (!empty($activeDatabaseName)) {
            $this->setActiveDatabase($activeDatabaseName);
        }
    }

    public function getActiveDatabaseName()
    {
        return $this->activeDatabaseName;
    }

    /**
     * @param $databaseName
     * @throws SchnoopException
     */
    public function setActiveDatabase($databaseName)
    {
        if (!$this->hasDatabase($databaseName)) {
            throw new SchnoopException(
                "Unknown database, `$databaseName`."
            );
        }

        $this->activeDatabaseName = $databaseName;
    }

    public function getDatabaseList()
    {
        return array_values($this->databaseList);
    }

    /**
     * @param $databaseName
     * @return bool
     */
    public function hasDatabase($databaseName)
    {
        return isset($this->databaseList[$databaseName]);
    }

    /**
     * @return DatabaseInterface|null
     */
    public function getDatabase()
    {
        $this->ensureActiveDatabaseSet();

        return $this->schemaFactory->createDatabase($this->dbInspector->fetchDatabase($this->activeDatabaseName));
    }

    /**
     * @return array
     */
    public function getTableList()
    {
        $this->ensureActiveDatabaseSet();

        return $this->dbInspector->fetchTableList($this->activeDatabaseName);
    }

    /**
     * @param $tableName
     * @return TableInterface
     */
    public function getTable($tableName)
    {
        $this->ensureActiveDatabaseSet();

        return $this->schemaFactory->createTable(
            $this->dbInspector->fetchTable($this->activeDatabaseName, $tableName),
            $this->dbInspector->fetchColumns($this->activeDatabaseName, $tableName),
            $this->dbInspector->fetchIndexes($this->activeDatabaseName, $tableName)
        );
    }
    
    protected function loadDatabaseList()
    {
        $databaseList = $this->dbInspector->fetchDatabaseList();
        $this->databaseList = array_combine($databaseList, $databaseList);
    }

    protected function ensureActiveDatabaseSet($failMessage = '')
    {
        if (empty($this->activeDatabaseName)) {
            throw new SchnoopException(
                !empty($failMessage) ? $failMessage : 'The active database has not been set.'
            );
        }
    }
}
