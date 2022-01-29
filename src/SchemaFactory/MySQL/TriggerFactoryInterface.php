<?php

namespace MilesAsylum\Schnoop\SchemaFactory\MySQL;

interface TriggerFactoryInterface
{
    /**
     * Fetch the triggers for the specified table.
     *
     * @param string $tableName
     * @param string $databaseName
     *
     * @return \MilesAsylum\Schnoop\SchemaAdapter\MySQL\TriggerInterface[]
     */
    public function fetch($tableName, $databaseName);
}
