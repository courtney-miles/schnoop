<?php

namespace MilesAsylum\Schnoop\Schema;

interface ColumnInterface
{
    public function getName();

    /**
     * @return DataTypeInterface
     */
    public function getDataType();
    
    public function getTable();
    
    public function setTable(TableInterface $table);

    public function __toString();
}
