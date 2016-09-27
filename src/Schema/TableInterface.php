<?php

namespace MilesAsylum\Schnoop\Schema;

use MilesAsylum\Schnoop\Schnoop;
use MilesAsylum\SchnoopSchema\MySQL\Table\TableInterface as SSTableInterface;
use MilesAsylum\SchnoopSchema\MySQL\Trigger\TriggerInterface;

interface TableInterface extends SSTableInterface
{
    /**
     * @param Schnoop $schnoop
     */
    public function setSchnoop(Schnoop $schnoop);

    /**
     * @return TriggerInterface
     */
    public function getTriggers();
}
