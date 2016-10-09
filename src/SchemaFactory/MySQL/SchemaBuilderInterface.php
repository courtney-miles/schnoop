<?php

namespace MilesAsylum\Schnoop\SchemaFactory\MySQL;

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
     * @param $databaseName
     * @param $tableName
     * @return TableInterface
     */
    public function fetchTable($databaseName, $tableName);

    /**
     * @param $databaseName
     * @param $tableName
     * @return TriggerInterface[]
     */
    public function fetchTriggers($databaseName, $tableName);
}
