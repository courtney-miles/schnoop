<?php

namespace MilesAsylum\Schnoop\Inspector;

interface InspectorInterface
{
    public function fetchDatabaseList();

    public function fetchTableList($databaseName);

    public function fetchActiveDatabase();
}
