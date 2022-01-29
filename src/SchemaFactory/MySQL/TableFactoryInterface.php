<?php

namespace MilesAsylum\Schnoop\SchemaFactory\MySQL;

use MilesAsylum\Schnoop\SchemaAdapter\MySQL\TableInterface;

interface TableFactoryInterface
{
    /**
     * Fetch a table from the database.
     *
     * @param string $tableName
     * @param string $databaseName
     *
     * @return TableInterface
     */
    public function fetch($tableName, $databaseName);
}
