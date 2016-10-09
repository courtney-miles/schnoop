<?php

namespace MilesAsylum\Schnoop\SchemaFactory\MySQL;

use MilesAsylum\Schnoop\SchemaAdapter\MySQL\TableInterface;

interface TableFactoryInterface
{
    /**
     * @param $tableName
     * @param $databaseName
     * @return TableInterface
     */
    public function fetch($tableName, $databaseName);
}
