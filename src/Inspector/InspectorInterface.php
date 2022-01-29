<?php

namespace MilesAsylum\Schnoop\Inspector;

interface InspectorInterface
{
    /**
     * Fetch the names of all the databases on the server.
     *
     * @return array
     */
    public function fetchDatabaseList();

    /**
     * Fetch the names of all the tables within the supplied database.
     *
     * @param string $databaseName
     *
     * @return array
     */
    public function fetchTableList($databaseName);

    /**
     * Fetch the names of all functions within the supplied database.
     *
     * @param string $databaseName
     *
     * @return array
     */
    public function fetchFunctionList($databaseName);

    /**
     * Fetch the names of all the procedures within the supplied database;.
     *
     * @param string $databaseName
     *
     * @return mixed
     */
    public function fetchProcedureList($databaseName);

    /**
     * Fetch the names of the triggers for the supplied table.
     *
     * @param string $databaseName
     * @param string $tableName
     *
     * @return array
     */
    public function fetchTriggerList($databaseName, $tableName);

    /**
     * Fetch the name of active database.
     *
     * @return string
     */
    public function fetchActiveDatabase();

    /**
     * Get the PDO connection used to inspect the database.
     *
     * @return \PDO
     */
    public function getPDO();
}
