<?php

namespace MilesAsylum\Schnoop\SchemaFactory;

interface SchemaBuilderInterface
{
    public function createDatabase($databaseName);

    public function createTable($databaseName, $tableName);
}
