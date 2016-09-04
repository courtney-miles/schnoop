<?php

namespace MilesAsylum\Schnoop\SchemaFactory;

interface SchemaBuilderInterface
{
    public function fetchDatabase($databaseName);

    public function fetchTable($databaseName, $tableName);
}
