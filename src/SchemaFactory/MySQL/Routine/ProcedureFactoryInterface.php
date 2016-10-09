<?php
namespace MilesAsylum\Schnoop\SchemaFactory\MySQL\Routine;

use MilesAsylum\Schnoop\SchemaAdapter\MySQL\RoutineProcedure;

interface ProcedureFactoryInterface extends \MilesAsylum\Schnoop\SchemaFactory\MySQL\ProcedureFactoryInterface
{
    public function fetchRaw($databaseName, $functionName);

    /**
     * @param array $raw
     * @return RoutineProcedure
     */
    public function createFromRaw(array $raw);

    /**
     * @param $name
     * @return RoutineProcedure
     */
    public function newProcedure($name);
}