<?php

namespace MilesAsylum\Schnoop\SchemaFactory\MySQL;

use MilesAsylum\Schnoop\SchemaAdapter\MySQL\RoutineProcedure;

interface ProcedureFactoryInterface
{
    /**
     * @param $databaseName
     * @param $functionName
     * @return RoutineProcedure
     */
    public function fetch($databaseName, $functionName);
}
