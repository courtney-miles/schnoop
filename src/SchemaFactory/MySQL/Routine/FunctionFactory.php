<?php

namespace MilesAsylum\Schnoop\SchemaFactory\MySQL\Routine;

use MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType\DataTypeFactoryInterface;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\SetVar\SqlModeFactory;
use MilesAsylum\SchnoopSchema\MySQL\DataType\DataTypeInterface;
use MilesAsylum\Schnoop\SchemaAdapter\MySQL\RoutineFunction;

class FunctionFactory extends AbstractRoutineFactory implements FunctionFactoryInterface
{
    /**
     * @var DataTypeFactoryInterface
     */
    protected $dataTypeFactory;

    /**
     * FunctionFactory constructor.
     * @param \PDO $pdo
     * @param ParametersFactory $parametersFactory
     * @param SqlModeFactory $sqlModeFactory
     * @param DataTypeFactoryInterface $dataTypeFactory
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

    /**
     * {@inheritdoc}
     */
    public function fetch($functionName, $databaseName)
    {
        return $this->createFromRaw(
            $this->fetchRaw($functionName, $databaseName)
        );
    }

    /**
     * {@inheritdoc}
     */
    public function fetchRaw($functionName, $databaseName)
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
     * {@inheritdoc}
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
     * {@inheritdoc}
     */
    public function newFunction($name, DataTypeInterface $returns)
    {
        return new RoutineFunction($name, $returns);
    }
}
