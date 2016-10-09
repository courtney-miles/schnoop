<?php

namespace MilesAsylum\Schnoop\SchemaFactory\MySQL\Column;

interface ColumnFactoryInterface
{
    public function fetch($databaseName, $tableName);
}
