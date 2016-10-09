<?php

namespace MilesAsylum\Schnoop\SchemaFactory\MySQL;

use MilesAsylum\Schnoop\SchemaAdapter\MySQL\TableInterface;

interface TableFactoryInterface
{
    /**
     * @param $databaseName
     * @param $tableName
     * @return TableInterface
     */
    public function fetch($databaseName, $tableName);
}
