<?php

namespace MilesAsylum\Schnoop\Schema;

/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 2/06/16
 * Time: 7:29 AM
 */
class AbstractCommonTable implements CommonTableInterface
{
    protected $name;

    /**
     * @var CommonColumnInterface[]
     */
    protected $columns = array();

    /**
     * @var CommonIndexInterface[]
     */
    protected $indexes = array();

    /**
     * AbstractTable constructor.
     * @param $name
     * @param CommonColumnInterface[] $columns
     * @param CommonIndexInterface[] $indexes
     */
    public function __construct($name, array $columns, array $indexes)
    {
        $this->name = $name;
        $this->setColumns($columns);
        $this->setIndexes($indexes);
    }
    
    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    public function getColumnList()
    {
        return array_keys($this->columns);
    }

    /**
     * @return AbstractCommonColumn[]
     */
    public function getColumns()
    {
        return array_values($this->columns);
    }

    public function hasColumn($columnName)
    {
        return isset($this->columns[$columnName]);
    }

    public function getColumn($columnName)
    {
        return $this->hasColumn($columnName) ? $this->columns[$columnName] : null;
    }

    public function getIndexList()
    {
        return array_keys($this->indexes);
    }

    public function getIndexes()
    {
        return array_values($this->indexes);
    }

    public function hasIndex($indexName)
    {
        return isset($this->indexes[$indexName]);
    }

    public function getIndex($indexName)
    {
        return $this->hasIndex($indexName) ? $this->indexes[$indexName] : null;
    }

    /**
     * @param CommonColumnInterface[] $columns
     */
    protected function setColumns(array $columns)
    {
        $this->columns = [];

        foreach ($columns as $column) {
            $this->addColumn($column);
        }
    }
    
    /**
     * @param CommonColumnInterface $column
     */
    protected function addColumn(CommonColumnInterface $column)
    {
        $column->setTable($this);
        $this->columns[$column->getName()] = $column;
    }

    /**
     * @param CommonIndexInterface[] $indexes
     */
    protected function setIndexes(array $indexes)
    {
        $this->indexes = [];

        foreach ($indexes as $index) {
            $this->addIndex($index);
        }
    }

    protected function addIndex(CommonIndexInterface $index)
    {
        $this->indexes[$index->getName()] = $index;
    }
}
