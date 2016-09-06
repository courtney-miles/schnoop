<?php

namespace MilesAsylum\Schnoop\SchemaFactory;

use MilesAsylum\SchnoopSchema\MySQL\Database\DatabaseInterface;
use MilesAsylum\SchnoopSchema\MySQL\Table\TableInterface;

interface SchemaBuilderInterface
{
    /**
     * @param $databaseName
     * @return DatabaseInterface
     */
    public function fetchDatabase($databaseName);

    /**
     * @param $databaseName
     * @param $tableName
     * @return TableInterface
     */
    public function fetchTable($databaseName, $tableName);
}
