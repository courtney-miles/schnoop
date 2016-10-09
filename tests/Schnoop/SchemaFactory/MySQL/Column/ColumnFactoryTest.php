<?php

namespace MilesAsylum\Schnoop\Tests\Schnoop\SchemaFactory\MySQL\Column;

use MilesAsylum\Schnoop\PHPUnit\Framework\TestMySQLCase;
use MilesAsylum\Schnoop\PHPUnit\Schnoop\MockPdo;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType\DataTypeFactoryInterface;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\Column\ColumnFactory;
use MilesAsylum\SchnoopSchema\MySQL\Column\Column;
use MilesAsylum\SchnoopSchema\MySQL\Column\ColumnInterface;
use MilesAsylum\SchnoopSchema\MySQL\DataType\DataTypeInterface;
use PHPUnit_Framework_MockObject_MockObject;

class ColumnFactoryTest extends TestMySQLCase
{
    /**
     * @var ColumnFactory
     */
    protected $columnMapper;

    /**
     * @var DataTypeFactoryInterface|PHPUnit_Framework_MockObject_MockObject
     */
    protected $mockDataTypeMapper;

    protected $tableName;

    protected $databaseName;

    public function setUp()
    {
        parent::setUp();

        $this->tableName = 'schnoop_tbl';
        $this->databaseName = $this->getDatabaseName();

        $this->getConnection()->query(<<<SQL
DROP TABLE IF EXISTS `{$this->databaseName}`.`{$this->tableName}` 
SQL
        );

        $this->getConnection()->query(<<<SQL
CREATE TABLE `{$this->databaseName}`.`{$this->tableName}` (
  id INTEGER AUTO_INCREMENT PRIMARY KEY NOT NULL COMMENT 'ID comment.',
  name VARCHAR(20) NULL COLLATE utf8mb4_general_ci COMMENT 'Name comment.'
)
SQL
        );

        $this->mockDataTypeMapper = $this->createMock(DataTypeFactoryInterface::class);

        $this->columnMapper = new ColumnFactory($this->getConnection(), $this->mockDataTypeMapper);
    }

    public function testFetchRaw()
    {
        $expectedRaw = [
            [
                'Field' => 'id',
                'Type' => 'int(11)',
                'Collation' => null,
                'Null' => 'NO',
                'Default' => null,
                'Extra' => 'auto_increment',
                'Comment' => 'ID comment.'
            ],
            [
                'Field' => 'name',
                'Type' => 'varchar(20)',
                'Collation' => 'utf8mb4_general_ci',
                'Null' => 'YES',
                'Default' => null,
                'Extra' => '',
                'Comment' => 'Name comment.'
            ]
        ];

        $this->assertSame(
            $expectedRaw,
            $this->columnMapper->fetchRaw($this->databaseName, $this->tableName)
        );
    }

    public function testNewColumn()
    {
        $name = 'schnoop_col';
        $mockDataType = $this->createMock(DataTypeInterface::class);

        $column = $this->columnMapper->newColumn($name, $mockDataType);

        $this->assertInstanceOf(Column::class, $column);
        $this->assertSame($name, $column->getName());
        $this->assertSame($mockDataType, $column->getDataType());
    }

    /**
     * @dataProvider rawColumnProvider
     * @param array $rawColumn
     * @param bool $expectedNullable
     * @param bool $expectedAutoIncrement
     * @param bool $expectedOnUpdateCurrentTimestamp
     */
    public function testCreateFromRaw(
        array $rawColumn,
        $expectedNullable,
        $expectedAutoIncrement,
        $expectedOnUpdateCurrentTimestamp
    ) {
        $mockDataType = $this->createMock(DataTypeInterface::class);

        $this->mockDataTypeMapper->expects($this->once())
            ->method('createType')
            ->with($rawColumn['Type'], $rawColumn['Collation'])
            ->willReturn($mockDataType);

        $mockColumn = $this->createMock(ColumnInterface::class);
        $mockColumn->expects($this->once())
            ->method('setNullable')
            ->with($expectedNullable);
        $mockColumn->expects($this->once())
            ->method('setAutoIncrement')
            ->with($expectedAutoIncrement);
        $mockColumn->expects($this->once())
            ->method('setDefault')
            ->with($rawColumn['Default']);
        $mockColumn->expects($this->once())
            ->method('setOnUpdateCurrentTimestamp')
            ->with($expectedOnUpdateCurrentTimestamp);
        $mockColumn->expects($this->once())
            ->method('setComment')
            ->with($rawColumn['Comment']);

        /** @var ColumnFactory|PHPUnit_Framework_MockObject_MockObject $mockColumnMapper */
        $mockColumnMapper = $this->getMockBuilder(ColumnFactory::class)
            ->setMethods(['newColumn'])
            ->setConstructorArgs(
                [
                    $this->createMock(MockPdo::class),
                    $this->mockDataTypeMapper
                ]
            )
            ->getMock();
        $mockColumnMapper->expects($this->once())
            ->method('newColumn')
            ->with($rawColumn['Field'], $mockDataType)
            ->willReturn($mockColumn);

        $this->assertSame($mockColumn, $mockColumnMapper->createFromRaw($rawColumn));
    }

    public function testFetch()
    {
        $databaseName = 'schnoop_db';
        $tableName = 'schnoop_tbl';
        $rawForTable = [
            ['foo'],
            ['bar']
        ];

        $mockColumn = $this->createMock(ColumnInterface::class);
        $mockColumn->expects($this->exactly(count($rawForTable)))
            ->method('setTableName')
            ->with($tableName);

        /** @var ColumnFactory|PHPUnit_Framework_MockObject_MockObject $mockColumnMapper */
        $mockColumnMapper = $this->getMockBuilder(ColumnFactory::class)
            ->setMethods(
                [
                    'fetchRaw',
                    'createFromRaw'
                ]
            )
            ->setConstructorArgs(
                [
                    $this->createMock(MockPdo::class),
                    $this->mockDataTypeMapper
                ]
            )
            ->getMock();
        $mockColumnMapper->expects($this->once())
            ->method('fetchRaw')
            ->with($databaseName, $tableName)
            ->willReturn($rawForTable);
        $mockColumnMapper->expects($this->exactly(count($rawForTable)))
            ->method('createFromRaw')
            ->withConsecutive(
                [$rawForTable[0]],
                [$rawForTable[1]]
            )
            ->willReturnOnConsecutiveCalls(
                $mockColumn,
                $mockColumn
            );

        $this->assertSame(
            [$mockColumn, $mockColumn],
            $mockColumnMapper->fetch($databaseName, $tableName)
        );
    }

    /**
     * @see testCreateFromRaw
     * @return array
     */
    public function rawColumnProvider()
    {
        return [
            [
                [
                    'Field' => 'id',
                    'Type' => 'int(11)',
                    'Collation' => null,
                    'Null' => 'NO',
                    'Default' => null,
                    'Extra' => 'auto_increment',
                    'Comment' => 'ID comment.'
                ],
                false,
                true,
                false
            ],
            [
                [
                    'Field' => 'id',
                    'Type' => 'timestamp',
                    'Collation' => null,
                    'Null' => 'NO',
                    'Default' => ColumnInterface::DEFAULT_CURRENT_TIMESTAMP,
                    'Extra' => 'on update CURRENT_TIMESTAMP',
                    'Comment' => 'ID comment.'
                ],
                false,
                false,
                true
            ]
        ];
    }
}
