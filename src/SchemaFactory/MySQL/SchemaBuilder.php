<?php

namespace MilesAsylum\Schnoop\SchemaFactory\MySQL;

use MilesAsylum\Schnoop\SchemaFactory\ColumnMapperInterface;
use MilesAsylum\Schnoop\SchemaFactory\DatabaseMapperInterface;
use MilesAsylum\Schnoop\SchemaFactory\ForeignKeyMapperInterface;
use MilesAsylum\Schnoop\SchemaFactory\IndexMapperInterface;
use MilesAsylum\Schnoop\SchemaFactory\DataTypeFactoryInterface;
use MilesAsylum\Schnoop\SchemaFactory\SchemaBuilderInterface;
use MilesAsylum\Schnoop\SchemaFactory\TableMapperInterface;

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
     * @var DataTypeFactoryInterface
     */
    private $dataTypeMapper;

    public function __construct(
        DatabaseMapperInterface $databaseMapper,
        TableMapperInterface $tableMapper,
        ColumnMapperInterface $columnMapper,
        IndexMapperInterface $indexMapper,
        ForeignKeyMapperInterface $foreignKeyMapper,
        DataTypeFactoryInterface $dataTypeMapper
    ) {
        $this->databaseMapper = $databaseMapper;
        $this->tableMapper = $tableMapper;
        $this->columnMapper = $columnMapper;
        $this->indexMapper = $indexMapper;
        $this->foreignKeyMapper = $foreignKeyMapper;
        $this->dataTypeMapper = $dataTypeMapper;
    }

    public function createDatabase($databaseName)
    {
        $this->databaseMapper->fetch($databaseName);
    }

    public function createTable($databaseName, $tableName)
    {
        $table = $this->tableMapper->fetch($databaseName, $tableName);
        $table->setColumns(
            $this->createColumns($databaseName, $tableName)
        );
        $table->setIndexes(
            $this->createIndexes($databaseName, $tableName)
        );
        $table->setForeignKey(
            $this->createForeignKeys($databaseName, $tableName)
        );
    }

    protected function createColumns($databaseName, $tableName)
    {
        return $this->columnMapper->fetchForTable($databaseName, $tableName);
    }

    protected function createIndexes($databaseName, $tableName)
    {
        return $this->indexMapper->fetchForTable($databaseName, $tableName);
    }

    protected function createForeignKeys($databaseName, $tableName)
    {
        return $this->foreignKeyMapper->fetchForTable($databaseName, $tableName);
    }
}
