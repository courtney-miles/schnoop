<?php

namespace MilesAsylum\Schnoop\Schema;

use MilesAsylum\Schnoop\Schnoop;
use MilesAsylum\SchnoopSchema\MySQL\Database\Database as SSDatabase;

class Database extends SSDatabase implements DatabaseInterface
{
    /**
     * @var Schnoop
     */
    private $schnoop;

    public function setSchnoop(Schnoop $schnoop)
    {
        $this->schnoop = $schnoop;
    }

    public function getTableList()
    {
        return $this->schnoop->getTableList($this->getName());
    }

    public function getTable($tableName)
    {
        return $this->schnoop->getTable($this->getName(), $tableName);
    }

    public function hasTable($tableName)
    {
        return $this->schnoop->hasTable($this->getName(), $tableName);
    }
}
