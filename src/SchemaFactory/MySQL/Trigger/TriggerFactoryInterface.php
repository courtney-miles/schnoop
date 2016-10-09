<?php
namespace MilesAsylum\Schnoop\SchemaFactory\MySQL\Trigger;

interface TriggerFactoryInterface extends \MilesAsylum\Schnoop\SchemaFactory\MySQL\TriggerFactoryInterface
{
    public function fetchRaw($tableName, $databaseName);

    public function createFromRaw(array $rawTriggers, $databaseName);

    public function newTrigger($name, $timing, $event, $tableName);
}
