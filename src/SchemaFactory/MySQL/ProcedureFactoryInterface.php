<?php

namespace MilesAsylum\Schnoop\SchemaFactory\MySQL;

use MilesAsylum\Schnoop\SchemaAdapter\MySQL\RoutineProcedure;

interface ProcedureFactoryInterface
{
    /**
     * @param $procedureName
     * @param $databaseName
     * @return RoutineProcedure
     */
    public function fetch($procedureName, $databaseName);
}
