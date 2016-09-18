<?php

namespace MilesAsylum\Schnoop\Tests\Schnoop\SchemaFactory\MySQL\Routine;

use MilesAsylum\Schnoop\PHPUnit\Framework\TestMySQLCase;
use MilesAsylum\Schnoop\PHPUnit\Schnoop\MockPdo;
use MilesAsylum\Schnoop\SchemaFactory\DataTypeFactoryInterface;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\Routine\FunctionMapper;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\Routine\ParametersFactory;
use MilesAsylum\SchnoopSchema\MySQL\DataType\DataTypeInterface;
use MilesAsylum\SchnoopSchema\MySQL\Routine\FunctionParameter;
use MilesAsylum\SchnoopSchema\MySQL\Routine\FunctionParameterInterface;
use MilesAsylum\SchnoopSchema\MySQL\Routine\FunctionRoutine;
use MilesAsylum\SchnoopSchema\MySQL\SetVar\SqlMode;
use PHPUnit_Framework_MockObject_MockObject;

class FunctionMapperTest extends TestMySQLCase
{
    protected $functionName;

    protected $databaseName;

    protected $definer;

    /**
     * @var DataTypeFactoryInterface|PHPUnit_Framework_MockObject_MockObject
     */
    protected $mockDataTypeFactory;

    /**
     * @var ParametersFactory|PHPUnit_Framework_MockObject_MockObject
     */
    protected $mockParametersFactory;

    /**
     * @var FunctionMapper
     */
    protected $functionMapper;

    public function setUp()
    {
        parent::setUp();

        $this->functionName = 'schnoop_func';
        $this->databaseName = $this->getDatabaseName();
        $this->definer = $this->getDatabaseUser() . '@' . $this->getDatabaseHost();
        $this->mockDataTypeFactory = $this->createMock(DataTypeFactoryInterface::class);
        $this->mockParametersFactory = $this->createMock(ParametersFactory::class);

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

        $this->functionMapper = new FunctionMapper(
            $this->getConnection(),
            $this->mockParametersFactory,
            $this->mockDataTypeFactory
        );
    }

    public function testNewSqlMode()
    {
        $mode = 'FOO';
        $sqlMode = $this->functionMapper->newSqlMode($mode);

        $this->assertInstanceOf(SqlMode::class, $sqlMode);
        $this->assertSame($mode, $sqlMode->getMode());
    }

    public function testNewFunction()
    {
        $returns = $this->createMock(DataTypeInterface::class);
        $function = $this->functionMapper->newFunction($this->functionName, $returns);

        $this->assertInstanceOf(FunctionRoutine::class, $function);
        $this->assertSame($this->functionName, $function->getName());
        $this->assertSame($returns, $function->getReturns());
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
            'sql_mode' => '',
            'comment' => 'Function comment.'
        ];

        $this->assertSame($expectedRaw, $this->functionMapper->fetchRaw($this->databaseName, $this->functionName));
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
            'sql_mode' => 'TRADITIONAL',
            'comment' => 'Function comment.'
        ];

        $mockParameters = [
            $this->createMock(FunctionParameterInterface::class),
            $this->createMock(FunctionParameterInterface::class)
        ];

        $mockReturnType = $this->createMock(DataTypeInterface::class);
        $mockSqlMode = $this->createMock(SqlMode::class);

        $mockFunction = $this->createMock(FunctionRoutine::class);
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

        /** @var FunctionMapper|PHPUnit_Framework_MockObject_MockObject $functionMapper */
        $functionMapper = $this->getMockBuilder(FunctionMapper::class)
            ->setConstructorArgs([$this->createMock(MockPdo::class), $this->mockParametersFactory, $mockDataTypeFactory])
            ->setMethods(['newFunction', 'newSqlMode'])
            ->getMock();
        $functionMapper->method('newFunction')
            ->with($raw['name'], $mockReturnType)
            ->willReturn($mockFunction);
        $functionMapper->method('newSqlMode')
            ->with($raw['sql_mode'])
            ->willReturn($mockSqlMode);

        $this->assertSame($mockFunction, $functionMapper->createFromRaw($raw));
    }

    public function testFetch()
    {
        $raw = ['foo'];

        $mockDataTypeFactory = $this->createMock(DataTypeFactoryInterface::class);

        $mockFunction = $this->createMock(FunctionRoutine::class);

        /** @var FunctionMapper|PHPUnit_Framework_MockObject_MockObject $functionMapper */
        $functionMapper = $this->getMockBuilder(FunctionMapper::class)
            ->setConstructorArgs([$this->createMock(MockPdo::class), $this->mockParametersFactory, $mockDataTypeFactory])
            ->setMethods(['fetchRaw', 'createFromRaw'])
            ->getMock();
        $functionMapper->expects($this->once())
            ->method('fetchRaw')
            ->with($this->databaseName, $this->functionName)
            ->willReturn($raw);
        $functionMapper->expects($this->once())
            ->method('createFromRaw')
            ->with($raw)
            ->willReturn($mockFunction);

        $this->assertSame(
            $mockFunction,
            $functionMapper->fetch($this->databaseName, $this->functionName)
        );
    }
}
