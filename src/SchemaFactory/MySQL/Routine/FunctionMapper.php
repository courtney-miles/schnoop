<?php

namespace MilesAsylum\Schnoop\SchemaFactory\MySQL\Routine;

use MilesAsylum\Schnoop\SchemaFactory\DataTypeFactoryInterface;
use MilesAsylum\SchnoopSchema\MySQL\DataType\DataTypeInterface;
use MilesAsylum\SchnoopSchema\MySQL\Routine\FunctionRoutine;

class FunctionMapper extends AbstractRoutineMapper
{
    /**
     * @var DataTypeFactoryInterface
     */
    protected $dataTypeFactory;

    public function __construct(
        \PDO $pdo,
        ParametersFactory $parametersFactory,
        DataTypeFactoryInterface $dataTypeFactory
    ) {
        parent::__construct($pdo, $parametersFactory);

        $this->dataTypeFactory = $dataTypeFactory;
    }

    /**
     * @param $databaseName
     * @param $functionName
     * @return FunctionRoutine
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
     * @return FunctionRoutine
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
     * @return FunctionRoutine
     */
    public function newFunction($name, DataTypeInterface $returns)
    {
        return new FunctionRoutine($name, $returns);
    }
}
