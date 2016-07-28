<?php

namespace MilesAsylum\Schnoop\DbInspector;

interface DbInspectorInterface
{
    public function fetchDatabaseList();

    /**
     * Fetch the details of the named database, or the current database if no name is supplied.
     * @param null $databaseName
     * @return mixed
     */
    public function fetchDatabase($databaseName = null);

    public function fetchTableList($databaseName);

    public function fetchTable($databaseName, $tableName);

    public function fetchColumns($databaseName, $tableName);

    public function fetchIndexes($databaseName, $tableName);

    public function fetchTriggers($databaseName, $tableName);

    public function fetchFunctionList($databaseName);

    public function fetchFunction($databaseName, $functionName);

    public function fetchProcedureList($databaseName);

    public function fetchProcedure($databaseName, $procedureName);
}
