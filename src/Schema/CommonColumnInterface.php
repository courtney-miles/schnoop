<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 3/06/16
 * Time: 7:36 AM
 */

namespace MilesAsylum\Schnoop\Schema;

interface CommonColumnInterface
{
    public function getName();

    /**
     * @return CommonDataTypeInterface
     */
    public function getDataType();
    
    public function getTable();
    
    public function setTable(CommonTableInterface $table);
}