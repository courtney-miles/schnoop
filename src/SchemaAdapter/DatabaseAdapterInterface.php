<?php

namespace MilesAsylum\Schnoop\SchemaAdapter;

use MilesAsylum\SchnoopSchema\MySQL\Database\DatabaseInterface;

interface DatabaseAdapterInterface extends DatabaseInterface
{
    public function getTableList();

    public function getTable($tableName);
}
