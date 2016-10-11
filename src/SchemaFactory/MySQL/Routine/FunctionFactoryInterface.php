<?php
namespace MilesAsylum\Schnoop\SchemaFactory\MySQL\Routine;

use MilesAsylum\Schnoop\SchemaAdapter\MySQL\RoutineFunction;
use MilesAsylum\SchnoopSchema\MySQL\DataType\DataTypeInterface;

interface FunctionFactoryInterface extends \MilesAsylum\Schnoop\SchemaFactory\MySQL\FunctionFactoryInterface
{
    /**
     * Fetch the raw rows from the database for the stored function.
     * @param string $functionName
     * @param string $databaseName
     * @return array Row data that defines function.
     */
    public function fetchRaw($functionName, $databaseName);

    /**
     * Create a stored function from the supplied row data.
     * @param array $raw Row data.
     * @return RoutineFunction
     */
    public function createFromRaw(array $raw);

    /**
     * Create a new stored function object.
     * @param string $name Function name
     * @param DataTypeInterface $returns The data type the function will return.
     * @return RoutineFunction
     */
    public function newFunction($name, DataTypeInterface $returns);
}
