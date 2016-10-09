<?php

namespace MilesAsylum\Schnoop\Tests\Schnoop\SchemaFactory\MySQL\Routine;

use MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType\DataTypeFactoryInterface;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\Routine\ParametersFactory;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\Routine\ParametersParser;
use MilesAsylum\SchnoopSchema\MySQL\DataType\DataTypeInterface;
use MilesAsylum\SchnoopSchema\MySQL\Routine\FunctionParameter;
use MilesAsylum\SchnoopSchema\MySQL\Routine\FunctionParameterInterface;
use MilesAsylum\SchnoopSchema\MySQL\Routine\ProcedureParameter;
use MilesAsylum\SchnoopSchema\MySQL\Routine\ProcedureParameterInterface;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;

class ParametersFactoryTest extends TestCase
{
    /**
     * @var ParametersFactory
     */
    protected $parametersFactory;

    /**
     * @var ParametersParser|PHPUnit_Framework_MockObject_MockObject
     */
    protected $mockParser;

    /**
     * @var DataTypeFactoryInterface|PHPUnit_Framework_MockObject_MockObject
     */
    protected $mockDataTypeFactory;

    public function setUp()
    {
        parent::setUp();

        $this->mockParser = $this->createMock(ParametersParser::class);
        $this->mockDataTypeFactory = $this->createMock(DataTypeFactoryInterface::class);

        $this->parametersFactory = new ParametersFactory($this->mockParser, $this->mockDataTypeFactory);
    }

    public function testNewFunctionParameter()
    {
        $name = 'foo';
        $mockDataType = $this->createMock(DataTypeInterface::class);

        $functionParam = $this->parametersFactory->newFunctionParameter($name, $mockDataType);

        $this->assertInstanceOf(FunctionParameter::class, $functionParam);
        $this->assertSame($name, $functionParam->getName());
        $this->assertSame($mockDataType, $functionParam->getDataType());
    }

    public function testNewProcedureParameter()
    {
        $name = 'foo';
        $mockDataType = $this->createMock(DataTypeInterface::class);
        $direction = 'INOUT';

        $procedureParameter = $this->parametersFactory->newProcedureParameter($name, $mockDataType, $direction);

        $this->assertInstanceOf(ProcedureParameter::class, $procedureParameter);
        $this->assertSame($name, $procedureParameter->getName());
        $this->assertSame($mockDataType, $procedureParameter->getDataType());
        $this->assertSame($direction, $procedureParameter->getDirection());
    }

    public function testCreateParameters()
    {
        $parametersStr = "__param_str__";
        $parsedParams = [
            [
                'direction' => null,
                'name' => 'schnoop_func_param',
                'dataType' => 'VARCHAR(20)'
            ],
            [
                'direction' => 'INOUT',
                'name' => 'schnoop_proc_param',
                'dataType' => 'INT(10)'
            ],
        ];
        $mockDataTypes = [
            $this->createMock(DataTypeInterface::class),
            $this->createMock(DataTypeInterface::class)
        ];

        /** @var ParametersFactory|PHPUnit_Framework_MockObject_MockObject $parametersFactory */
        $parametersFactory = $this->getMockBuilder(ParametersFactory::class)
            ->setConstructorArgs([$this->mockParser, $this->mockDataTypeFactory])
            ->setMethods(['newFunctionParameter', 'newProcedureParameter'])
            ->getMock();

        $this->mockParser->expects($this->once())
            ->method('parse')
            ->with($parametersStr)
            ->willReturn($parsedParams);

        $this->mockDataTypeFactory->expects($this->exactly(2))
            ->method('createType')
            ->withConsecutive(
                [$parsedParams[0]['dataType'], null],
                [$parsedParams[1]['dataType'], null]
            )->willReturnOnConsecutiveCalls(
                $mockDataTypes[0],
                $mockDataTypes[1]
            );

        $mockFunctionParam = $this->createMock(FunctionParameterInterface::class);
        $parametersFactory->expects($this->once())
            ->method('newFunctionParameter')
            ->with($parsedParams[0]['name'], $mockDataTypes[0])
            ->willReturn($mockFunctionParam);

        $mockProcedureParam = $this->createMock(ProcedureParameterInterface::class);
        $parametersFactory->expects($this->once())
            ->method('newProcedureParameter')
            ->with($parsedParams[1]['name'], $mockDataTypes[1])
            ->willReturn($mockProcedureParam);

        $parametersFactory->createParameters($parametersStr);
    }
}
