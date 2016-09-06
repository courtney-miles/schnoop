<?php

namespace MilesAsylum\Schnoop\SchemaAdapter;

use MilesAsylum\SchnoopSchema\MySQL\Database\DatabaseInterface;
use MilesAsylum\SchnoopSchema\MySQL\Table\TableInterface;

interface DatabaseAdapterInterface extends DatabaseInterface
{
    public function getTableList();

    /**
     * @param $tableName
     * @return TableInterface
     */
    public function getTable($tableName);

    public function hasTable($tableName);
}
