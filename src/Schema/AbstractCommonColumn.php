<?php

namespace MilesAsylum\Schnoop\Schema;

use MilesAsylum\Schnoop\Schema\Exception\ColumnException;

/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 2/06/16
 * Time: 7:30 AM
 */
abstract class AbstractCommonColumn implements CommonColumnInterface
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var CommonDataTypeInterface
     */
    protected $dataType;

    /**
     * @var CommonTableInterface
     */
    protected $table;

    /**
     * AbstractColumn constructor.
     * @param string $name
     * @param CommonDataTypeInterface $dataType
     */
    public function __construct($name, CommonDataTypeInterface $dataType)
    {
        $this->setName($name);
        $this->setDataType($dataType);
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }
    
    public function getTable()
    {
        return $this->table;
    }

    /**
     * @return CommonDataTypeInterface
     */
    public function getDataType()
    {
        return $this->dataType;
    }

    /**
     * @param mixed $name
     */
    protected function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @param CommonDataTypeInterface $dataType
     */
    protected function setDataType(CommonDataTypeInterface $dataType)
    {
        $this->dataType = $dataType;
    }

    public function setTable(CommonTableInterface $table)
    {
        if (isset($this->table)) {
            throw new ColumnException(
                sprintf(
                    'Attempt made to attach column %s to table %s when it is already attached to %s',
                    $this->getName(),
                    $table->getName(),
                    $this->table->getName()
                )
            );
        }

        $this->table = $table;
    }
}