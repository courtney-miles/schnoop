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
     * Set the Schnoop object
     * @param Schnoop $schnoop
     */
    public function setSchnoop(Schnoop $schnoop);

    /**
     * Fetch a database from the server.
     * @param string $databaseName
     * @return DatabaseInterface
     */
    public function fetchDatabase($databaseName);

    /**
     * Fetch a table from the database.
     * @param string $tableName
     * @param string $databaseName
     * @return TableInterface
     */
    public function fetchTable($tableName, $databaseName);

    /**
     * Fetch the triggers for a table.
     * @param string $tableName
     * @param string $databaseName
     * @return \MilesAsylum\Schnoop\SchemaAdapter\MySQL\TriggerInterface[]
     */
    public function fetchTriggers($tableName, $databaseName);

    /**
     * Fetch a function from the database.
     * @param string $functionName
     * @param string $databaseName
     * @return RoutineFunctionInterface
     */
    public function fetchFunction($functionName, $databaseName);

    /**
     * Fetch a procedure from the database.
     * @param string $procedureName
     * @param string $databaseName
     * @return RoutineProcedureInterface
     */
    public function fetchProcedure($procedureName, $databaseName);
}
