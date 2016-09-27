<?php

namespace MilesAsylum\Schnoop\SchemaFactory;

use MilesAsylum\Schnoop\SchemaFactory\MySQL\Trigger\TriggerMapper;
use MilesAsylum\Schnoop\Schnoop;

class SchemaBuilder implements SchemaBuilderInterface
{
    /**
     * @var DatabaseMapperInterface
     */
    private $databaseMapper;

    /**
     * @var TableMapperInterface
     */
    private $tableMapper;

    /**
     * @var ColumnMapperInterface
     */
    private $columnMapper;

    /**
     * @var IndexMapperInterface
     */
    private $indexMapper;

    /**
     * @var ForeignKeyMapperInterface
     */
    private $foreignKeyMapper;

    /**
     * @var TriggerMapperInterface
     */
    private $triggerMapper;

    /**
     * @var Schnoop
     */
    private $schnoop;

    public function __construct(
        DatabaseMapperInterface $databaseMapper,
        TableMapperInterface $tableMapper,
        ColumnMapperInterface $columnMapper,
        IndexMapperInterface $indexMapper,
        ForeignKeyMapperInterface $foreignKeyMapper,
        TriggerMapperInterface $triggerMapper
    ) {
        $this->databaseMapper = $databaseMapper;
        $this->tableMapper = $tableMapper;
        $this->columnMapper = $columnMapper;
        $this->indexMapper = $indexMapper;
        $this->foreignKeyMapper = $foreignKeyMapper;
        $this->triggerMapper = $triggerMapper;
    }

    /**
     * {@inheritdoc}
     */
    public function setSchnoop(Schnoop $schnoop)
    {
        $this->schnoop = $schnoop;
    }

    /**
     * {@inheritdoc}
     */
    public function fetchDatabase($databaseName)
    {
        $database = $this->databaseMapper->fetch($databaseName);
        $database->setSchnoop($this->schnoop);

        return $database;
    }

    /**
     * {@inheritdoc}
     */
    public function fetchTable($databaseName, $tableName)
    {
        $table = $this->tableMapper->fetch($databaseName, $tableName);
        $table->setSchnoop($this->schnoop);
        $table->setColumns(
            $this->fetchColumns($databaseName, $tableName)
        );
        $table->setIndexes(
            $this->fetchIndexes($databaseName, $tableName)
        );
        $table->setForeignKeys(
            $this->fetchForeignKeys($databaseName, $tableName)
        );

        return $table;
    }

    /**
     * {@inheritdoc}
     */
    public function fetchTriggers($databaseName, $tableName)
    {
        return $this->triggerMapper->fetch($databaseName, $tableName);
    }

    protected function fetchColumns($databaseName, $tableName)
    {
        return $this->columnMapper->fetch($databaseName, $tableName);
    }

    protected function fetchIndexes($databaseName, $tableName)
    {
        return $this->indexMapper->fetch($databaseName, $tableName);
    }

    protected function fetchForeignKeys($databaseName, $tableName)
    {
        return $this->foreignKeyMapper->fetch($databaseName, $tableName);
    }
}
