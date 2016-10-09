<?php

namespace MilesAsylum\Schnoop\Tests\Schnoop\SchemaFactory\MySQL\Routine;

use MilesAsylum\Schnoop\PHPUnit\Framework\TestMySQLCase;
use MilesAsylum\Schnoop\PHPUnit\Schnoop\MockPdo;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType\DataTypeFactoryInterface;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\Routine\ParametersFactory;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\Routine\ProcedureFactory;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\SetVar\SqlModeFactory;
use MilesAsylum\SchnoopSchema\MySQL\Routine\ProcedureParameterInterface;
use MilesAsylum\Schnoop\SchemaAdapter\MySQL\RoutineProcedure;
use MilesAsylum\SchnoopSchema\MySQL\SetVar\SqlMode;
use PHPUnit_Framework_MockObject_MockObject;

class ProcedureFactoryTest extends TestMySQLCase
{
    protected $procedureName;

    protected $databaseName;

    protected $definer;

    /**
     * @var ParametersFactory|PHPUnit_Framework_MockObject_MockObject
     */
    protected $mockParametersFactory;

    /**
     * @var SqlModeFactory|PHPUnit_Framework_MockObject_MockObject
     */
    protected $mockSqlModeFactory;

    /**
     * @var ProcedureFactory
     */
    protected $procedureMapper;

    public function setUp()
    {
        parent::setUp();

        $this->procedureName = 'schnoop_proc';
        $this->databaseName = $this->getDatabaseName();
        $this->definer = $this->getDatabaseUser() . '@' . $this->getDatabaseHost();
        $this->mockParametersFactory = $this->createMock(ParametersFactory::class);
        $this->mockSqlModeFactory = $this->createMock(SqlModeFactory::class);

        $this->getConnection()->exec(<<<SQL
DROP PROCEDURE IF EXISTS `{$this->databaseName}`.`{$this->procedureName}` 
SQL
        );

        $this->getConnection()->exec(<<<SQL
CREATE DEFINER={$this->definer} PROCEDURE `{$this->databaseName}`.`{$this->procedureName}` (IN needle VARCHAR(20), INOUT haystack VARCHAR(20))
DETERMINISTIC
COMMENT 'Function comment.'
BEGIN
  SELECT 1;
END
SQL
        );

        $this->procedureMapper = new ProcedureFactory(
            $this->getConnection(),
            $this->mockParametersFactory,
            $this->mockSqlModeFactory
        );
    }

    public function testNewFunction()
    {
        $function = $this->procedureMapper->newProcedure($this->procedureName);

        $this->assertInstanceOf(RoutineProcedure::class, $function);
        $this->assertSame($this->procedureName, $function->getName());
    }

    public function testFetchRaw()
    {
        $expectedRaw = [
            'name' => $this->procedureName,
            'sql_data_access' => 'CONTAINS_SQL',
            'is_deterministic' => 'YES',
            'security_type' => 'DEFINER',
            'param_list' => 'IN needle VARCHAR(20), INOUT haystack VARCHAR(20)',
            'returns' => '',
            'body' => 'BEGIN
  SELECT 1;
END',
            'definer' => $this->definer,
            'sql_mode' => '',
            'comment' => 'Function comment.'
        ];

        $this->assertSame($expectedRaw, $this->procedureMapper->fetchRaw($this->databaseName, $this->procedureName));
    }

    public function testCreateFromRaw()
    {
        $raw = [
            'name' => $this->procedureName,
            'sql_data_access' => 'CONTAINS_SQL',
            'is_deterministic' => 'YES',
            'security_type' => 'DEFINER',
            'param_list' => 'IN needle VARCHAR(20), INOUT haystack VARCHAR(20)',
            'returns' => '',
            'body' => 'BEGIN
  SELECT 1;
END',
            'definer' => $this->definer,
            'sql_mode' => '',
            'comment' => 'Function comment.'
        ];

        $mockParameters = [
            $this->createMock(ProcedureParameterInterface::class),
            $this->createMock(ProcedureParameterInterface::class)
        ];

        $mockSqlMode = $this->createMock(SqlMode::class);

        $this->mockSqlModeFactory->method('newSqlMode')
            ->with($raw['sql_mode'])
            ->willReturn($mockSqlMode);

        $mockProcedure = $this->createMock(RoutineProcedure::class);
        $mockProcedure->expects($this->once())
            ->method('setDefiner')
            ->with($raw['definer']);
        $mockProcedure->expects($this->once())
            ->method('setParameters')
            ->with($mockParameters);
        $mockProcedure->expects($this->once())
            ->method('setDataAccess')
            ->with('CONTAINS SQL');
        $mockProcedure->expects($this->once())
            ->method('setDeterministic')
            ->with(true);
        $mockProcedure->expects($this->once())
            ->method('setSqlSecurity')
            ->with($raw['security_type']);
        $mockProcedure->expects($this->once())
            ->method('setComment')
            ->with($raw['comment']);
        $mockProcedure->expects($this->once())
            ->method('setSqlMode')
            ->with($mockSqlMode);

        $mockDataTypeFactory = $this->createMock(DataTypeFactoryInterface::class);

        $this->mockParametersFactory->expects($this->once())
            ->method('createParameters')
            ->with($raw['param_list'])
            ->willReturn($mockParameters);

        /** @var ProcedureFactory|PHPUnit_Framework_MockObject_MockObject $procedureMapper */
        $procedureMapper = $this->getMockBuilder(ProcedureFactory::class)
            ->setConstructorArgs(
                [
                    $this->createMock(MockPdo::class),
                    $this->mockParametersFactory,
                    $this->mockSqlModeFactory
                ]
            )
            ->setMethods(['newProcedure', 'newSqlMode'])
            ->getMock();
        $procedureMapper->method('newProcedure')
            ->with($raw['name'])
            ->willReturn($mockProcedure);

        $this->assertSame($mockProcedure, $procedureMapper->createFromRaw($raw));
    }

    public function testFetch()
    {
        $raw = ['foo'];

        $mockProcedure = $this->createMock(RoutineProcedure::class);

        /** @var ProcedureFactory|PHPUnit_Framework_MockObject_MockObject $procedureMapper */
        $procedureMapper = $this->getMockBuilder(ProcedureFactory::class)
            ->setConstructorArgs(
                [
                    $this->createMock(MockPdo::class),
                    $this->mockParametersFactory,
                    $this->mockSqlModeFactory
                ]
            )
            ->setMethods(['fetchRaw', 'createFromRaw'])
            ->getMock();
        $procedureMapper->expects($this->once())
            ->method('fetchRaw')
            ->with($this->databaseName, $this->procedureName)
            ->willReturn($raw);
        $procedureMapper->expects($this->once())
            ->method('createFromRaw')
            ->with($raw)
            ->willReturn($mockProcedure);

        $this->assertSame(
            $mockProcedure,
            $procedureMapper->fetch($this->databaseName, $this->procedureName)
        );
    }
}
