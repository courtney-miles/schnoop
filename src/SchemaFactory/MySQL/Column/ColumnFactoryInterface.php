<?php

namespace MilesAsylum\Schnoop\SchemaFactory\MySQL\Column;

interface ColumnFactoryInterface
{
    public function fetch($tableName, $databaseName);
}
