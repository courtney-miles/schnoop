<?php

namespace MilesAsylum\Schnoop\SchemaFactory\MySQL\Column;

use MilesAsylum\SchnoopSchema\MySQL\Column\Column;
use MilesAsylum\SchnoopSchema\MySQL\DataType\DataTypeInterface;

interface ColumnFactoryInterface
{
    /**
     * Fetch the constructed columns for a table.
     * @param string $tableName
     * @param string $databaseName
     * @return \MilesAsylum\SchnoopSchema\MySQL\Column\Column[]
     */
    public function fetch($tableName, $databaseName);

    /**
     * Fetch the raw rows from the database for table columns.
     * @param string $databaseName
     * @param string $tableName
     * @return array
     */
    public function fetchRaw($databaseName, $tableName);

    /**
     * Create a column object from the row data.
     * @param array $rawColumn
     * @return Column
     */
    public function createFromRaw(array $rawColumn);

    /**
     * Construct a new Column object.
     * @param string $name
     * @param DataTypeInterface $dataType
     * @return Column
     */
    public function newColumn($name, DataTypeInterface $dataType);
}
