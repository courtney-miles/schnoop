<?php

namespace MilesAsylum\Schnoop\SchemaFactory;

interface ColumnMapperInterface
{
    public function fetch($databaseName, $tableName);
}
