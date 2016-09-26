<?php

namespace MilesAsylum\Schnoop\SchemaFactory;

use MilesAsylum\Schnoop\Schema\TableInterface;

interface TableMapperInterface
{
    /**
     * @param $databaseName
     * @param $tableName
     * @return TableInterface
     */
    public function fetch($databaseName, $tableName);
}
