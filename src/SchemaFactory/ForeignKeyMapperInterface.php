<?php

namespace MilesAsylum\Schnoop\SchemaFactory;

interface ForeignKeyMapperInterface
{
    public function fetch($databaseName, $tableName);
}
