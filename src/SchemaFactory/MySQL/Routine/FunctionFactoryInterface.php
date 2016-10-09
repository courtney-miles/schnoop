<?php
namespace MilesAsylum\Schnoop\SchemaFactory\MySQL\Routine;

use MilesAsylum\Schnoop\SchemaAdapter\MySQL\RoutineFunction;
use MilesAsylum\SchnoopSchema\MySQL\DataType\DataTypeInterface;

interface FunctionFactoryInterface extends \MilesAsylum\Schnoop\SchemaFactory\MySQL\FunctionFactoryInterface
{
    /**
     * @param $databaseName
     * @param $functionName
     * @return RoutineFunction
     */
    public function fetch($databaseName, $functionName);

    public function fetchRaw($databaseName, $functionName);

    /**
     * @param array $raw
     * @return RoutineFunction
     */
    public function createFromRaw(array $raw);

    /**
     * @param $name
     * @param DataTypeInterface $returns
     * @return RoutineFunction
     */
    public function newFunction($name, DataTypeInterface $returns);
}