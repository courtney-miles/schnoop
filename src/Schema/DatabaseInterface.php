<?php

namespace MilesAsylum\Schnoop\Schema;

use MilesAsylum\Schnoop\Schnoop;
use MilesAsylum\SchnoopSchema\MySQL\Database\DatabaseInterface as SSDatabaseInterface;
use MilesAsylum\SchnoopSchema\MySQL\Table\TableInterface;

interface DatabaseInterface extends SSDatabaseInterface
{
    public function setSchnoop(Schnoop $schnoop);

    public function getTableList();

    /**
     * @param $tableName
     * @return TableInterface
     */
    public function getTable($tableName);

    public function hasTable($tableName);
}
