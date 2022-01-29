<?php

namespace MilesAsylum\Schnoop\SchemaFactory\MySQL\Routine;

use MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType\DataTypeFactoryInterface;
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
     */
    public function __construct(ParametersParser $parametersParser, DataTypeFactoryInterface $dataTypeFactory)
    {
        $this->parametersParser = $parametersParser;
        $this->dataTypeFactory = $dataTypeFactory;
    }

    /**
     * Create collection of parameter objects from the supplied string.
     *
     * @param string $parametersString
     *
     * @return FunctionParameter[]|ProcedureParameter[]
     */
    public function createParameters($parametersString)
    {
        $parameters = [];

        $rawParameters = $this->parametersParser->parse($parametersString);

        foreach ($rawParameters as $rawParameter) {
            $dataType = $this->dataTypeFactory->createType($rawParameter['dataType']);

            if (null !== $rawParameter['direction']) {
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

    /**
     * Create a new parameter for a function.
     *
     * @param string $name parameter name
     *
     * @return FunctionParameter
     */
    public function newFunctionParameter($name, DataTypeInterface $dataType)
    {
        return new FunctionParameter($name, $dataType);
    }

    /**
     * Create a new parameter for a procedure.
     *
     * @param string $name      parameter name
     * @param string $direction The parameter value direction.  One of
     *                          ProcedureParameterInterface::DIRECTION_* constants.
     *
     * @return ProcedureParameter
     */
    public function newProcedureParameter($name, DataTypeInterface $dataType, $direction)
    {
        return new ProcedureParameter($name, $dataType, $direction);
    }
}
