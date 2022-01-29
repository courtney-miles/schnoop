<?php

namespace MilesAsylum\Schnoop\SchemaFactory\MySQL\Constraint;

use MilesAsylum\SchnoopSchema\MySQL\Constraint\IndexInterface;

interface IndexFactoryInterface
{
    /**
     * Fetch the constructed indexes for the specified table.
     *
     * @param string $tableName
     * @param string $databaseName
     *
     * @return IndexInterface[]
     */
    public function fetch($tableName, $databaseName);

    /**
     * Fetch the raw rows from the database for table indexes.
     *
     * @param string $databaseName
     * @param string $tableName
     *
     * @return array
     */
    public function fetchRaw($databaseName, $tableName);

    /**
     * Construct the indexes from the supplied raw row data.
     *
     * @return \MilesAsylum\SchnoopSchema\MySQL\Constraint\IndexInterface[]
     */
    public function createFromRaw(array $rawTableIndexes);
}
