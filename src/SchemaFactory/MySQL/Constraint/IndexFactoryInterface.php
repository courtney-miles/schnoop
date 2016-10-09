<?php

namespace MilesAsylum\Schnoop\SchemaFactory\MySQL\Constraint;

interface IndexFactoryInterface
{
    public function fetch($tableName, $databaseName);
}
