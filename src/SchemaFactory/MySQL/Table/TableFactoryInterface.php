<?php
namespace MilesAsylum\Schnoop\SchemaFactory\MySQL\Table;

interface TableFactoryInterface extends \MilesAsylum\Schnoop\SchemaFactory\MySQL\TableFactoryInterface
{
    public function fetchRaw($databaseName, $tableName);

    public function createFromRaw(array $rawTable, $databaseName);

    public function newTable($databaseName, $tableName);
}
