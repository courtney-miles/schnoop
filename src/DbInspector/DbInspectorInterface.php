<?php

namespace MilesAsylum\Schnoop\DbInspector;

interface DbInspectorInterface
{
    public function fetchDatabaseList();
    
    public function fetchDatabase($databaseName);

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