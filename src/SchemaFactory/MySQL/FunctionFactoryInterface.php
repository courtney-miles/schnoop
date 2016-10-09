<?php

namespace MilesAsylum\Schnoop\SchemaFactory\MySQL;

use MilesAsylum\Schnoop\SchemaAdapter\MySQL\RoutineFunction;

interface FunctionFactoryInterface
{
    /**
     * @param $databaseName
     * @param $functionName
     * @return RoutineFunction
     */
    public function fetch($databaseName, $functionName);
}
