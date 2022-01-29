<?php

namespace MilesAsylum\Schnoop\SchemaFactory\MySQL;

use MilesAsylum\Schnoop\SchemaFactory\MySQL\Column\ColumnFactoryInterface;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\Constraint\ForeignKeyFactoryInterface;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\Constraint\IndexFactoryInterface;
use MilesAsylum\Schnoop\Schnoop;

class SchemaBuilder implements SchemaBuilderInterface
{
    /**
     * @var DatabaseFactoryInterface
     */
    private $databaseMapper;

    /**
     * @var TableFactoryInterface
     */
    private $tableMapper;

    /**
     * @var ColumnFactoryInterface
     */
    private $columnMapper;

    /**
     * @var IndexFactoryInterface
     */
    private $indexMapper;

    /**
     * @var ForeignKeyFactoryInterface
     */
    private $foreignKeyMapper;

    /**
     * @var TriggerFactoryInterface
     */
    private $triggerMapper;

    /**
     * @var FunctionFactoryInterface
     */
    private $functionFactory;

    /**
     * @var ProcedureFactoryInterface
     */
    private $procedureFactory;

    /**
     * @var Schnoop
     */
    private $schnoop;

    /**
     * SchemaBuilder constructor.
     */
    public function __construct(
        DatabaseFactoryInterface $databaseMapper,
        TableFactoryInterface $tableMapper,
        ColumnFactoryInterface $columnMapper,
        IndexFactoryInterface $indexMapper,
        ForeignKeyFactoryInterface $foreignKeyMapper,
        TriggerFactoryInterface $triggerMapper,
        FunctionFactoryInterface $functionFactory,
        ProcedureFactoryInterface $procedureFactory
    ) {
        $this->databaseMapper = $databaseMapper;
        $this->tableMapper = $tableMapper;
        $this->columnMapper = $columnMapper;
        $this->indexMapper = $indexMapper;
        $this->foreignKeyMapper = $foreignKeyMapper;
        $this->triggerMapper = $triggerMapper;
        $this->functionFactory = $functionFactory;
        $this->procedureFactory = $procedureFactory;
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
    public function fetchTable($tableName, $databaseName)
    {
        $table = $this->tableMapper->fetch($tableName, $databaseName);
        $table->setSchnoop($this->schnoop);
        $table->setColumns(
            $this->fetchColumns($tableName, $databaseName)
        );
        $table->setIndexes(
            $this->fetchIndexes($tableName, $databaseName)
        );
        $table->setForeignKeys(
            $this->fetchForeignKeys($tableName, $databaseName)
        );

        return $table;
    }

    /**
     * {@inheritdoc}
     */
    public function fetchTriggers($tableName, $databaseName)
    {
        return $this->triggerMapper->fetch($tableName, $databaseName);
    }

    /**
     * {@inheritdoc}
     */
    public function fetchFunction($functionName, $databaseName)
    {
        return $this->functionFactory->fetch($functionName, $databaseName);
    }

    /**
     * {@inheritdoc}
     */
    public function fetchProcedure($procedureName, $databaseName)
    {
        return $this->procedureFactory->fetch($procedureName, $databaseName);
    }

    /**
     * Fetch columns for a table.
     *
     * @param string $tableName
     * @param string $databaseName
     *
     * @return \MilesAsylum\SchnoopSchema\MySQL\Column\Column[]
     */
    protected function fetchColumns($tableName, $databaseName)
    {
        return $this->columnMapper->fetch($tableName, $databaseName);
    }

    /**
     * Fetch indexes for a table.
     *
     * @param string $tableName
     * @param string $databaseName
     *
     * @return \MilesAsylum\SchnoopSchema\MySQL\Constraint\IndexInterface[]
     */
    protected function fetchIndexes($tableName, $databaseName)
    {
        return $this->indexMapper->fetch($tableName, $databaseName);
    }

    /**
     * Fetch foreign keys for a table.
     *
     * @param string $tableName
     * @param string $databaseName
     *
     * @return \MilesAsylum\SchnoopSchema\MySQL\Constraint\ForeignKey[]
     */
    protected function fetchForeignKeys($tableName, $databaseName)
    {
        return $this->foreignKeyMapper->fetch($tableName, $databaseName);
    }
}
