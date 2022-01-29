<?php

namespace MilesAsylum\Schnoop\SchemaAdapter\MySQL;

use MilesAsylum\Schnoop\Schnoop;
use MilesAsylum\SchnoopSchema\MySQL\Table\TableInterface;

interface DatabaseInterface extends \MilesAsylum\SchnoopSchema\MySQL\Database\DatabaseInterface
{
    /**
     * Attach the Schnoop object to allow snooping from the database object.
     */
    public function setSchnoop(Schnoop $schnoop);

    /**
     * Get the names of the tables for this database.
     *
     * @return array
     */
    public function getTableList();

    /**
     * Get the named table from database.
     *
     * @param string $tableName
     *
     * @return TableInterface
     */
    public function getTable($tableName);

    /**
     * Identify if the named table exists in the database.
     *
     * @param string $tableName
     *
     * @return bool
     */
    public function hasTable($tableName);
}
