<?php

namespace MilesAsylum\Schnoop\SchemaFactory\MySQL\Constraint;

interface ForeignKeyFactoryInterface
{
    public function fetch($tableName, $databaseName);
}
