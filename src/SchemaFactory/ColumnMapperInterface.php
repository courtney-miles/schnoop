<?php

namespace MilesAsylum\Schnoop\SchemaFactory;

interface ColumnMapperInterface
{
    public function fetchForTable($databaseName, $tableName);
}
