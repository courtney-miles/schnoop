<?php
namespace MilesAsylum\Schnoop\SchemaFactory\MySQL\Table;

use MilesAsylum\Schnoop\SchemaAdapter\MySQL\Table;

interface TableFactoryInterface extends \MilesAsylum\Schnoop\SchemaFactory\MySQL\TableFactoryInterface
{
    /**
     * Fetch the raw rows from the database for defining a table.
     * @param string $tableName
     * @param string $databaseName
     * @return array Row data.
     */
    public function fetchRaw($tableName, $databaseName);

    /**
     * Create a table from the row data that defines the table.
     * @param array $rawTable
     * @param string $databaseName
     * @return Table
     */
    public function createFromRaw(array $rawTable, $databaseName);

    /**
     * Create a new table.
     * @param string $tableName
     * @param string $databaseName
     * @return Table
     */
    public function newTable($tableName, $databaseName);
}
