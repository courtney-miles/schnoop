<?php

namespace MilesAsylum\Schnoop\Inspector;

interface InspectorInterface
{
    public function fetchDatabaseList();

    public function fetchTableList($databaseName);

    public function fetchFunctionList($databaseName);

    public function fetchProcedureList($databaseName);

    public function fetchTriggerList($databaseName, $tableName);

    public function fetchActiveDatabase();
}
