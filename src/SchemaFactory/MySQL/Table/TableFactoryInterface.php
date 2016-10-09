<?php
namespace MilesAsylum\Schnoop\SchemaFactory\MySQL\Table;

interface TableFactoryInterface extends \MilesAsylum\Schnoop\SchemaFactory\MySQL\TableFactoryInterface
{
    public function fetchRaw($tableName, $databaseName);

    public function createFromRaw(array $rawTable, $databaseName);

    public function newTable($tableName, $databaseName);
}
