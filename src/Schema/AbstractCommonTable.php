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
     * AbstractTable constructor.
     * @param $name
     * @param CommonColumnInterface[] $columns
     */
    public function __construct($name, array $columns)
    {
        $this->name = $name;
        $this->setColumns($columns);
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
        return $this->columns[$columnName];
    }

    /**
     * @param CommonColumnInterface[] $columns
     */
    protected function setColumns(array $columns)
    {
        $this->columns = array();

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
}