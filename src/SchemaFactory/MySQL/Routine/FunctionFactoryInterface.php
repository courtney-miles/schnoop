<?php
namespace MilesAsylum\Schnoop\SchemaFactory\MySQL\Routine;

use MilesAsylum\Schnoop\SchemaAdapter\MySQL\RoutineFunction;
use MilesAsylum\SchnoopSchema\MySQL\DataType\DataTypeInterface;

interface FunctionFactoryInterface extends \MilesAsylum\Schnoop\SchemaFactory\MySQL\FunctionFactoryInterface
{
    public function fetchRaw($functionName, $databaseName);

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