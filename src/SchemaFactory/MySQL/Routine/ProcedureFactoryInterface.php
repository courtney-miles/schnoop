<?php

namespace MilesAsylum\Schnoop\SchemaFactory\MySQL\Routine;

use MilesAsylum\Schnoop\SchemaAdapter\MySQL\RoutineProcedure;

interface ProcedureFactoryInterface extends \MilesAsylum\Schnoop\SchemaFactory\MySQL\ProcedureFactoryInterface
{
    /**
     * Fetch the raw rows from the database for the stored procedure.
     *
     * @param string $functionName
     * @param string $databaseName
     *
     * @return array row data that defines the stored procedure
     */
    public function fetchRaw($functionName, $databaseName);

    /**
     * Create a stored procedure from the supplied row data.
     *
     * @param array $raw row data
     *
     * @return RoutineProcedure
     */
    public function createFromRaw(array $raw);

    /**
     * Create a new stored procedure.
     *
     * @param string $name procedure name
     *
     * @return RoutineProcedure
     */
    public function newProcedure($name);
}
