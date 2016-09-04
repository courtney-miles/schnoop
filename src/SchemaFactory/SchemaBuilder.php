<?php

namespace MilesAsylum\Schnoop\SchemaFactory;

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

    public function __construct(
        DatabaseMapperInterface $databaseMapper,
        TableMapperInterface $tableMapper,
        ColumnMapperInterface $columnMapper,
        IndexMapperInterface $indexMapper,
        ForeignKeyMapperInterface $foreignKeyMapper
    ) {
        $this->databaseMapper = $databaseMapper;
        $this->tableMapper = $tableMapper;
        $this->columnMapper = $columnMapper;
        $this->indexMapper = $indexMapper;
        $this->foreignKeyMapper = $foreignKeyMapper;
    }

    public function fetchDatabase($databaseName)
    {
        return $this->databaseMapper->fetch($databaseName);
    }

    public function fetchTable($databaseName, $tableName)
    {
        $table = $this->tableMapper->fetch($databaseName, $tableName);
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
