<?php

namespace MilesAsylum\Schnoop;

use MilesAsylum\Schnoop\DbInspector\DbInspectorInterface;
use PDO;
use MilesAsylum\Schnoop\Schema\FactoryInterface;
use MilesAsylum\Schnoop\Schema\CommonTableInterface;

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
     * @var array
     */
    protected $databaseList;

    /**
     * Schnoop constructor.
     * @param PDO $pdo
     * @param DbInspectorInterface $dbAdapter
     * @param FactoryInterface $factoryInterface
     */
    public function __construct(PDO $pdo, DbInspectorInterface $dbAdapter, FactoryInterface $factoryInterface)
    {
        $this->pdo = $pdo;
        $this->dbInspector = $dbAdapter;
        $this->schemaFactory = $factoryInterface;
        
        $this->loadDatabaseList();
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
     * @param $databaseName
     * @return Schema\CommonDatabaseInterface|null
     */
    public function getDatabase($databaseName)
    {
        return $this->hasDatabase($databaseName)
            ? $this->schemaFactory->createDatabase($this->dbInspector->fetchDatabase($databaseName), $this)
            : null;
    }

    /**
     * @param $databaseName
     * @return array
     */
    public function getTableList($databaseName)
    {
        return $this->dbInspector->fetchTableList($databaseName);
    }

    /**
     * @param $databaseName
     * @param $tableName
     * @return CommonTableInterface
     */
    public function getTable($databaseName, $tableName)
    {
        return $this->schemaFactory->createTable(
            $this->dbInspector->fetchTable($databaseName, $tableName),
            $this->dbInspector->fetchColumns($databaseName, $tableName)
        );
    }
    
    protected function loadDatabaseList()
    {
        $databaseList = $this->dbInspector->fetchDatabaseList();
        $this->databaseList = array_combine($databaseList, $databaseList);
    }
}