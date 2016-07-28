<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 4/06/16
 * Time: 6:04 PM
 */

namespace MilesAsylum\Schnoop\Schema;

interface CommonDatabaseInterface
{
    public function getName();

    public function getTableList();

    public function hasTable($tableName);

    public function getTable($tableName);
}
