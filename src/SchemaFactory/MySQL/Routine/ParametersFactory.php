<?php

namespace MilesAsylum\Schnoop\SchemaFactory\MySQL\Routine;

use MilesAsylum\Schnoop\SchemaFactory\DataTypeFactoryInterface;
use MilesAsylum\SchnoopSchema\MySQL\DataType\DataTypeInterface;
use MilesAsylum\SchnoopSchema\MySQL\Routine\FunctionParameter;
use MilesAsylum\SchnoopSchema\MySQL\Routine\ProcedureParameter;

class ParametersFactory
{
    /**
     * @var ParametersParser
     */
    private $parametersParser;

    /**
     * @var DataTypeFactoryInterface
     */
    private $dataTypeFactory;

    /**
     * ParametersFactory constructor.
     * @param ParametersParser $parametersParser
     * @param DataTypeFactoryInterface $dataTypeFactory
     */
    public function __construct(ParametersParser $parametersParser, DataTypeFactoryInterface $dataTypeFactory)
    {
        $this->parametersParser = $parametersParser;
        $this->dataTypeFactory = $dataTypeFactory;
    }

    public function createParameters($parametersString)
    {
        $parameters = [];

        $rawParameters = $this->parametersParser->parse($parametersString);

        foreach ($rawParameters as $rawParameter) {
            $dataType = $this->dataTypeFactory->createType($rawParameter['dataType']);

            if ($rawParameter['direction'] !== null) {
                $parameters[] = $this->newProcedureParameter(
                    $rawParameter['name'],
                    $dataType,
                    $rawParameter['direction']
                );
            } else {
                $parameters[] = $this->newFunctionParameter(
                    $rawParameter['name'],
                    $dataType
                );
            }
        }

        return $parameters;
    }

    public function newFunctionParameter($name, DataTypeInterface $dataType)
    {
        return new FunctionParameter($name, $dataType);
    }

    public function newProcedureParameter($name, DataTypeInterface $dataType, $direction)
    {
        return new ProcedureParameter($name, $dataType, $direction);
    }
}
