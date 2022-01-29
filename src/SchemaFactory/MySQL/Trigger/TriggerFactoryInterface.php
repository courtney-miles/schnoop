<?php

namespace MilesAsylum\Schnoop\SchemaFactory\MySQL\Trigger;

use MilesAsylum\Schnoop\SchemaAdapter\MySQL\Trigger;

interface TriggerFactoryInterface extends \MilesAsylum\Schnoop\SchemaFactory\MySQL\TriggerFactoryInterface
{
    /**
     * Fetch the raw row from the database that defines a trigger.
     *
     * @param string $tableName
     * @param string $databaseName
     *
     * @return array
     */
    public function fetchRaw($tableName, $databaseName);

    /**
     * Create a trigger from row data that defines the trigger.
     *
     * @param string $databaseName
     *
     * @return Trigger
     */
    public function createFromRaw(array $rawTriggers, $databaseName);

    /**
     * Create a new trigger.
     *
     * @param string $name
     * @param string $timing    one of Trigger::TIMING_* constants
     * @param string $event     one of Trigger::EVENT_* constants
     * @param string $tableName
     *
     * @return Trigger
     */
    public function newTrigger($name, $timing, $event, $tableName);
}
