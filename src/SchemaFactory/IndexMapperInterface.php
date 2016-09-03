<?php

namespace MilesAsylum\Schnoop\SchemaFactory;

interface IndexMapperInterface
{
    public function fetchForTable($databaseName, $tableName);
}
