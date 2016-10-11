<?php

namespace MilesAsylum\Schnoop\SchemaFactory\MySQL;

use MilesAsylum\Schnoop\SchemaAdapter\MySQL\RoutineProcedure;

interface ProcedureFactoryInterface
{
    /**
     * Fetch a procedure from the database.
     * @param string $procedureName
     * @param string $databaseName
     * @return RoutineProcedure
     */
    public function fetch($procedureName, $databaseName);
}
