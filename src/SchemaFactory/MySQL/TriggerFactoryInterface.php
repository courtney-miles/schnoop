<?php
namespace MilesAsylum\Schnoop\SchemaFactory\MySQL;

use MilesAsylum\Schnoop\SchemaAdapter\MySQL\TriggerInterface;

interface TriggerFactoryInterface
{
    /**
     * Fetch the triggers for the specified table.
     * @param string $databaseName
     * @param string $tableName
     * @return TriggerInterface[]
     */
    public function fetch($databaseName, $tableName);
}
