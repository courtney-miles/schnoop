<?php

namespace MilesAsylum\Schnoop\SchemaFactory;

interface ForeignKeyMapperInterface
{
    public function fetchForTable($databaseName, $tableName);
}
