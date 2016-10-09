<?php

namespace MilesAsylum\Schnoop\SchemaFactory\MySQL\Routine;

use MilesAsylum\Schnoop\SchemaFactory\DataTypeFactoryInterface;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\SetVar\SqlModeFactory;
use MilesAsylum\SchnoopSchema\MySQL\DataType\DataTypeInterface;
use MilesAsylum\SchnoopSchema\MySQL\Routine\RoutineFunction;

class FunctionMapper extends AbstractRoutineMapper
{
    /**
     * @var DataTypeFactoryInterface
     */
    protected $dataTypeFactory;

    public function __construct(
        \PDO $pdo,
        ParametersFactory $parametersFactory,
        SqlModeFactory $sqlModeFactory,
        DataTypeFactoryInterface $dataTypeFactory
    ) {
        parent::__construct($pdo, $parametersFactory, $sqlModeFactory);

        $this->dataTypeFactory = $dataTypeFactory;
    }

    /**
     * @param $databaseName
     * @param $functionName
     * @return RoutineFunction
     */
    public function fetch($databaseName, $functionName)
    {
        return $this->createFromRaw(
            $this->fetchRaw($databaseName, $functionName)
        );
    }

    public function fetchRaw($databaseName, $functionName)
    {
        $this->stmtSelectFunction->execute(
            [
                ':database' => $databaseName,
                ':type' => 'FUNCTION',
                ':function' => $functionName
            ]
        );

        return $this->stmtSelectFunction->fetch(\PDO::FETCH_ASSOC);
    }

    /**
     * @param array $raw
     * @return RoutineFunction
     */
    public function createFromRaw(array $raw)
    {
        $returnType = $this->dataTypeFactory->createType($raw['returns']);

        $function = $this->newFunction($raw['name'], $returnType);
        $this->hydrateRoutine($function, $raw);
        $function->setParameters($this->parametersFactory->createParameters($raw['param_list']));

        return $function;
    }

    /**
     * @param $name
     * @param DataTypeInterface $returns
     * @return RoutineFunction
     */
    public function newFunction($name, DataTypeInterface $returns)
    {
        return new RoutineFunction($name, $returns);
    }
}
