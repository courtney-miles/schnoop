<?php

namespace MilesAsylum\Schnoop\Schema;

use MilesAsylum\Schnoop\Schnoop;
use MilesAsylum\SchnoopSchema\MySQL\Table\TableInterface as SSTableInterface;

interface TableInterface extends SSTableInterface
{
    public function setSchnoop(Schnoop $schnoop);

    public function getTriggers();
}
