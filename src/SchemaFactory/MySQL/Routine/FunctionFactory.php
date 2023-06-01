<?php

namespace MilesAsylum\Schnoop\SchemaFactory\MySQL\Routine;

use MilesAsylum\Schnoop\SchemaAdapter\MySQL\RoutineFunction;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType\DataTypeFactoryInterface;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\SetVar\SqlModeFactory;
use MilesAsylum\SchnoopSchema\MySQL\DataType\DataTypeInterface;

class FunctionFactory extends AbstractRoutineFactory implements FunctionFactoryInterface
{
    /**
     * @var DataTypeFactoryInterface
     */
    protected $dataTypeFactory;

    /**
     * FunctionFactory constructor.
     */
    public function __construct(
        \PDO $pdo,
        ParametersFactory $parametersFactory,
        SqlModeFactory $sqlModeFactory,
        DataTypeFactoryInterface $dataTypeFactory
    ) {
        parent::__construct($pdo, $parametersFactory, $sqlModeFactory);

        $this->dataTypeFactory = $dataTypeFactory;
    }

    public function fetch($functionName, $databaseName)
    {
        return $this->createFromRaw(
            $this->fetchRaw($functionName, $databaseName)
        );
    }

    public function fetchRaw($functionName, $databaseName)
    {
        $this->stmtSelectFunction->execute(
            [
                ':database' => $databaseName,
                ':type' => 'FUNCTION',
                ':function' => $functionName,
            ]
        );

        return $this->stmtSelectFunction->fetch(\PDO::FETCH_ASSOC);
    }

    public function createFromRaw(array $raw)
    {
        $returnType = $this->dataTypeFactory->createType($raw['returns']);

        $function = $this->newFunction($raw['name'], $returnType);
        $this->hydrateRoutine($function, $raw);
        $function->setParameters($this->parametersFactory->createParameters($raw['param_list']));

        return $function;
    }

    public function newFunction($name, DataTypeInterface $returns)
    {
        return new RoutineFunction($name, $returns);
    }
}
