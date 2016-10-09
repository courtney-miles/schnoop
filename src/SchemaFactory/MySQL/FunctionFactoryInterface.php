<?php

namespace MilesAsylum\Schnoop\SchemaFactory\MySQL;

use MilesAsylum\Schnoop\SchemaAdapter\MySQL\RoutineFunction;

interface FunctionFactoryInterface
{
    /**
     * @param $functionName
     * @param $databaseName
     * @return RoutineFunction
     */
    public function fetch($functionName, $databaseName);
}
