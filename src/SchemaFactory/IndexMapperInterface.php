<?php

namespace MilesAsylum\Schnoop\SchemaFactory;

interface IndexMapperInterface
{
    public function fetch($databaseName, $tableName);
}
