<?php
namespace MilesAsylum\Schnoop\SchemaFactory\MySQL\Database;

use MilesAsylum\Schnoop\SchemaAdapter\MySQL\Database;

interface DatabaseFactoryInterface extends \MilesAsylum\Schnoop\SchemaFactory\MySQL\DatabaseFactoryInterface
{
    /**
     * Fetch the raw row from the database for a database.
     * @param $databaseName
     * @return mixed
     */
    public function fetchRaw($databaseName);

    /**
     * Create database object from raw row data.
     * @param array $rawDatabase
     * @return Database
     */
    public function createFromRaw(array $rawDatabase);

    /**
     * Create a new database object.
     * @param string $databaseName
     * @return Database
     */
    public function newDatabase($databaseName);
}
