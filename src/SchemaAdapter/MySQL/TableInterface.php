<?php

namespace MilesAsylum\Schnoop\SchemaAdapter\MySQL;

use MilesAsylum\Schnoop\Schnoop;

interface TableInterface extends \MilesAsylum\SchnoopSchema\MySQL\Table\TableInterface
{
    /**
     * Attach the Schnoop object to allow snooping from the table object.
     * @param Schnoop $schnoop
     */
    public function setSchnoop(Schnoop $schnoop);

    /**
     * Get the triggers for the table.
     * @return TriggerInterface[]
     */
    public function getTriggers();

    /**
     * Identify if the table has any triggers.
     * @return bool
     */
    public function hasTriggers();
}
