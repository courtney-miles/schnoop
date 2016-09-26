<?php
namespace MilesAsylum\Schnoop\SchemaFactory;

interface TriggerMapperInterface
{
    public function fetch($databaseName, $tableName);
}
