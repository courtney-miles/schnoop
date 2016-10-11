<?php

namespace MilesAsylum\Schnoop\SchemaFactory\MySQL;

use MilesAsylum\Schnoop\SchemaAdapter\MySQL\RoutineFunction;

interface FunctionFactoryInterface
{
    /**
     * Fetch a function from the database.
     * @param string $functionName
     * @param string $databaseName
     * @return RoutineFunction
     */
    public function fetch($functionName, $databaseName);
}
