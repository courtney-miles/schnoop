<?php

namespace MilesAsylum\Schnoop\SchemaFactory\MySQL;

use MilesAsylum\Schnoop\SchemaAdapter\MySQL\RoutineFunctionInterface;
use MilesAsylum\Schnoop\SchemaAdapter\MySQL\RoutineProcedureInterface;
use MilesAsylum\Schnoop\Schnoop;
use MilesAsylum\Schnoop\SchemaAdapter\MySQL\DatabaseInterface;
use MilesAsylum\Schnoop\SchemaAdapter\MySQL\TableInterface;
use MilesAsylum\Schnoop\SchemaAdapter\MySQL\TriggerInterface;

interface SchemaBuilderInterface
{
    /**
     * @param Schnoop $schnoop
     */
    public function setSchnoop(Schnoop $schnoop);

    /**
     * @param $databaseName
     * @return DatabaseInterface
     */
    public function fetchDatabase($databaseName);

    /**
     * @param $tableName
     * @param $databaseName
     * @return TableInterface
     */
    public function fetchTable($tableName, $databaseName);

    /**
     * @param $tableName
     * @param $databaseName
     * @return \MilesAsylum\Schnoop\SchemaAdapter\MySQL\TriggerInterface[]
     */
    public function fetchTriggers($tableName, $databaseName);

    /**
     * @param $functionName
     * @param $databaseName
     * @return RoutineFunctionInterface
     */
    public function fetchFunction($functionName, $databaseName);

    /**
     * @param $procedureName
     * @param $databaseName
     * @return RoutineProcedureInterface
     */
    public function fetchProcedure($procedureName, $databaseName);
}
