<?php

namespace MilesAsylum\Schnoop\SchemaFactory;

use MilesAsylum\SchnoopSchema\MySQL\Table\TableInterface;

interface TableMapperInterface
{
    /**
     * @param $databaseName
     * @param $tableName
     * @return TableInterface
     */
    public function fetch($databaseName, $tableName);
}
