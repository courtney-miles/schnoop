<?php

namespace MilesAsylum\Schnoop\SchemaFactory\MySQL\Constraint;

use MilesAsylum\SchnoopSchema\MySQL\Constraint\ForeignKey;
use MilesAsylum\SchnoopSchema\MySQL\Constraint\ForeignKeyColumn;

interface ForeignKeyFactoryInterface
{
    /**
     * Fetch the constructed foreign keys for a table.
     *
     * @param string $tableName
     * @param string $databaseName
     *
     * @return ForeignKey[]
     */
    public function fetch($tableName, $databaseName);

    /**
     * Fetch the raw rows from the database for the table foreign keys.
     *
     * @param string $databaseName
     * @param string $tableName
     *
     * @return array
     */
    public function fetchRaw($databaseName, $tableName);

    /**
     * Construct the foreign keys from the raw row data.
     *
     * @return \MilesAsylum\SchnoopSchema\MySQL\Constraint\ForeignKey[]
     */
    public function createFromRaw(array $rawTableFKs);

    /**
     * Construct a new Foreign Key object.
     *
     * @param string $keyName
     *
     * @return ForeignKey
     */
    public function newForeignKey($keyName);

    /**
     * Construct a new foreign key column.
     *
     * @param string $columnName          the foreign key column
     * @param string $referenceColumnName the name of the table the foreign key column refers to
     *
     * @return ForeignKeyColumn
     */
    public function newForeignKeyColumn($columnName, $referenceColumnName);
}
