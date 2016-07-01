<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 4/06/16
 * Time: 9:12 AM
 */

namespace MilesAsylum\Schnoop\Schema;

interface CommonTableInterface
{
    public function getName();
    public function getColumns();
    public function getColumn($columnName);
}