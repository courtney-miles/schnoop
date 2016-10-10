<?php

namespace MilesAsylum\Schnoop\SchemaAdapter\MySQL;

use MilesAsylum\Schnoop\Schnoop;

interface TableInterface extends \MilesAsylum\SchnoopSchema\MySQL\Table\TableInterface
{
    /**
     * @param Schnoop $schnoop
     */
    public function setSchnoop(Schnoop $schnoop);

    /**
     * @return TriggerInterface[]
     */
    public function getTriggers();

    public function hasTriggers();
}
