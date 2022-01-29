<?php

namespace MilesAsylum\Schnoop\Tests\Schnoop\SchemaFactory\MySQL\Routine;

use MilesAsylum\Schnoop\PHPUnit\Framework\TestMySQLCase;
use MilesAsylum\Schnoop\PHPUnit\Schnoop\MockPdo;
use MilesAsylum\Schnoop\SchemaAdapter\MySQL\RoutineFunction;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType\DataTypeFactoryInterface;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\Routine\FunctionFactory;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\Routine\ParametersFactory;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\SetVar\SqlModeFactory;
use MilesAsylum\SchnoopSchema\MySQL\DataType\DataTypeInterface;
use MilesAsylum\SchnoopSchema\MySQL\Routine\FunctionParameterInterface;
use MilesAsylum\SchnoopSchema\MySQL\SetVar\SqlMode;
use PHPUnit\Framework\MockObject\MockObject;

class FunctionFactoryTest extends TestMySQLCase
{
    protected $functionName;

    protected $databaseName;

    protected $definer;

    /**
     * @var DataTypeFactoryInterface|MockObject
     */
    protected $mockDataTypeFactory;

    /**
     * @var ParametersFactory|MockObject
     */
    protected $mockParametersFactory;

    /**
     * @var SqlModeFactory|MockObject
     */
    protected $mockSqlModeFactory;

    /**
     * @var FunctionFactory
     */
    protected $functionMapper;

    public function setUp()
    {
        parent::setUp();

        $this->functionName = 'schnoop_func';
        $this->databaseName = $this->getDatabaseName();
        $this->definer = $this->getDatabaseUser().'@'.$this->getDatabaseHost();
        $this->mockDataTypeFactory = $this->createMock(DataTypeFactoryInterface::class);
        $this->mockParametersFactory = $this->createMock(ParametersFactory::class);
        $this->mockSqlModeFactory = $this->createMock(SqlModeFactory::class);

        $this->getConnection()->exec(<<<SQL
DROP FUNCTION IF EXISTS `{$this->databaseName}`.`{$this->functionName}` 
SQL
        );

        $this->getConnection()->exec(<<<SQL
CREATE DEFINER={$this->definer} FUNCTION `{$this->databaseName}`.`{$this->functionName}` (needle VARCHAR(20), haystack VARCHAR(20))
RETURNS TINYINT(1)
DETERMINISTIC
COMMENT 'Function comment.'
BEGIN
  RETURN true;
END
SQL
        );

        $this->functionMapper = new FunctionFactory(
            $this->getConnection(),
            $this->mockParametersFactory,
            $this->mockSqlModeFactory,
            $this->mockDataTypeFactory
        );
    }

    public function testNewFunction()
    {
        $returns = $this->createMock(DataTypeInterface::class);
        $function = $this->functionMapper->newFunction($this->functionName, $returns);

        $this->assertInstanceOf(RoutineFunction::class, $function);
        $this->assertSame($this->functionName, $function->getName());
        $this->assertSame($returns, $function->getReturnType());
    }

    public function testFetchRaw()
    {
        $expectedRaw = [
            'name' => $this->functionName,
            'sql_data_access' => 'CONTAINS_SQL',
            'is_deterministic' => 'YES',
            'security_type' => 'DEFINER',
            'param_list' => 'needle VARCHAR(20), haystack VARCHAR(20)',
            'returns' => 'tinyint(1)',
            'body' => 'BEGIN
  RETURN true;
END',
            'definer' => $this->definer,
            'sql_mode' => $this->sqlMode,
            'comment' => 'Function comment.',
        ];

        $this->assertSame($expectedRaw, $this->functionMapper->fetchRaw($this->functionName, $this->databaseName));
    }

    public function testCreateFromRaw()
    {
        $raw = [
            'name' => $this->functionName,
            'sql_data_access' => 'CONTAINS_SQL',
            'is_deterministic' => 'YES',
            'security_type' => 'DEFINER',
            'param_list' => 'needle VARCHAR(20), haystack VARCHAR(20)',
            'returns' => 'tinyint(1)',
            'body' => 'BEGIN
  RETURN true;
END',
            'definer' => $this->definer,
            'sql_mode' => $this->sqlMode,
            'comment' => 'Function comment.',
        ];

        $mockParameters = [
            $this->createMock(FunctionParameterInterface::class),
            $this->createMock(FunctionParameterInterface::class),
        ];

        $mockReturnType = $this->createMock(DataTypeInterface::class);
        $mockSqlMode = $this->createMock(SqlMode::class);

        $this->mockSqlModeFactory->expects($this->once())
            ->method('newSqlMode')
            ->with($raw['sql_mode'])
            ->willReturn($mockSqlMode);

        $mockFunction = $this->createMock(RoutineFunction::class);
        $mockFunction->expects($this->once())
            ->method('setDefiner')
            ->with($raw['definer']);
        $mockFunction->expects($this->once())
            ->method('setParameters')
            ->with($mockParameters);
        $mockFunction->expects($this->once())
            ->method('setDataAccess')
            ->with('CONTAINS SQL');
        $mockFunction->expects($this->once())
            ->method('setDeterministic')
            ->with(true);
        $mockFunction->expects($this->once())
            ->method('setSqlSecurity')
            ->with($raw['security_type']);
        $mockFunction->expects($this->once())
            ->method('setComment')
            ->with($raw['comment']);
        $mockFunction->expects($this->once())
            ->method('setSqlMode')
            ->with($mockSqlMode);

        $mockDataTypeFactory = $this->createMock(DataTypeFactoryInterface::class);
        $mockDataTypeFactory->expects($this->once())
            ->method('createType')
            ->with($raw['returns'])
            ->willReturn($mockReturnType);

        $this->mockParametersFactory->expects($this->once())
            ->method('createParameters')
            ->with($raw['param_list'])
            ->willReturn($mockParameters);

        /** @var FunctionFactory|MockObject $functionMapper */
        $functionMapper = $this->getMockBuilder(FunctionFactory::class)
            ->setConstructorArgs(
                [
                    $this->createMock(MockPdo::class),
                    $this->mockParametersFactory,
                    $this->mockSqlModeFactory,
                    $mockDataTypeFactory,
                ]
            )
            ->setMethods(['newFunction', 'newSqlMode'])
            ->getMock();
        $functionMapper->method('newFunction')
            ->with($raw['name'], $mockReturnType)
            ->willReturn($mockFunction);

        $this->assertSame($mockFunction, $functionMapper->createFromRaw($raw));
    }

    public function testFetch()
    {
        $raw = ['foo'];

        $mockDataTypeFactory = $this->createMock(DataTypeFactoryInterface::class);

        $mockFunction = $this->createMock(RoutineFunction::class);

        /** @var FunctionFactory|MockObject $functionMapper */
        $functionMapper = $this->getMockBuilder(FunctionFactory::class)
            ->setConstructorArgs(
                [
                    $this->createMock(MockPdo::class),
                    $this->mockParametersFactory,
                    $this->mockSqlModeFactory,
                    $mockDataTypeFactory,
                ]
            )
            ->setMethods(['fetchRaw', 'createFromRaw'])
            ->getMock();
        $functionMapper->expects($this->once())
            ->method('fetchRaw')
            ->with($this->functionName, $this->databaseName)
            ->willReturn($raw);
        $functionMapper->expects($this->once())
            ->method('createFromRaw')
            ->with($raw)
            ->willReturn($mockFunction);

        $this->assertSame(
            $mockFunction,
            $functionMapper->fetch($this->functionName, $this->databaseName)
        );
    }
}
