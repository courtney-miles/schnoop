<?php

namespace MilesAsylum\Schnoop\Tests\Schnoop\SchemaFactory\MySQL\Constraint;

use MilesAsylum\Schnoop\PHPUnit\Framework\TestMySQLCase;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\Constraint\ForeignKeyMapper;
use MilesAsylum\SchnoopSchema\MySQL\Constraint\ForeignKey;
use MilesAsylum\SchnoopSchema\MySQL\Constraint\ForeignKeyColumn;
use MilesAsylum\SchnoopSchema\MySQL\Constraint\ForeignKeyColumnInterface;
use MilesAsylum\SchnoopSchema\MySQL\Constraint\ForeignKeyInterface;
use PHPUnit_Framework_MockObject_MockObject;

class ForeignKeyMapperTest extends TestMySQLCase
{
    /**
     * @var ForeignKeyMapper
     */
    protected $foreignKeyMapper;

    protected $tableName = 'schnoop_tbl';

    protected $referenceTableName = 'schnoop_ref_tbl';

    protected $databaseName;

    protected function setUp()
    {
        parent::setUp();

        $this->databaseName = $this->getDatabaseName();

        $this->getConnection()->query(<<<SQL
DROP TABLE IF EXISTS `{$this->databaseName}`.`{$this->tableName}` 
SQL
        );

        $this->getConnection()->query(<<<SQL
DROP TABLE IF EXISTS `{$this->databaseName}`.`{$this->referenceTableName}` 
SQL
        );

        $this->getConnection()->query(<<<SQL
CREATE TABLE `{$this->databaseName}`.`{$this->tableName}` (
  id INTEGER,
  name VARCHAR(100)
) ENGINE InnoDB
SQL
        );

        $this->getConnection()->query(<<<SQL
CREATE TABLE `{$this->databaseName}`.`{$this->referenceTableName}` (
  ref_id INTEGER,
  ref_name VARCHAR(100),
  UNIQUE INDEX (ref_id),
  UNIQUE INDEX (ref_name),
  UNIQUE INDEX (ref_id, ref_name)
) ENGINE InnoDB
SQL
        );

        $this->foreignKeyMapper = new ForeignKeyMapper($this->getConnection());
    }

    public function testNewForeignKey()
    {
        $keyName = 'fk_schnoop';
        $foreignKey = $this->foreignKeyMapper->newForeignKey($keyName);

        $this->assertInstanceOf(ForeignKey::class, $foreignKey);
        $this->assertSame($keyName, $foreignKey->getName());
    }

    public function testNewForeignKeyColumn()
    {
        $columnName = 'id';
        $referenceColumnName = 'ref_id';

        $foreignKeyColumn = $this->foreignKeyMapper->newForeignKeyColumn($columnName, $referenceColumnName);

        $this->assertInstanceOf(ForeignKeyColumn::class, $foreignKeyColumn);
        $this->assertSame($columnName, $foreignKeyColumn->getColumnName());
        $this->assertSame($referenceColumnName, $foreignKeyColumn->getReferenceColumnName());
    }

    public function testFetchRawForTable()
    {
        $this->getConnection()->query(<<<SQL
ALTER TABLE `{$this->databaseName}`.`{$this->tableName}`
  ADD CONSTRAINT `fk_schnoop`
    FOREIGN KEY (`id`)
    REFERENCES `{$this->databaseName}`.`{$this->referenceTableName}` (`ref_id`)
    ON UPDATE RESTRICT
    ON DELETE RESTRICT
SQL
        );

        $expectedRaw = [
            [
                'table_name' => 'schnoop_tbl',
                'constraint_name' => 'fk_schnoop',
                'column_name' => 'id',
                'ordinal_position' => '1',
                'referenced_table_name' => 'schnoop_ref_tbl',
                'referenced_column_name' => 'ref_id'
            ]
        ];

        $this->assertSame(
            $expectedRaw,
            $this->foreignKeyMapper->fetchRawForTable($this->databaseName, $this->tableName)
        );
    }

    /**
     * @dataProvider createFromRawTestData
     * @param array $foreignKeyExpectations
     * @param array $foreignKeyColumnExpectations
     * @param array $rawForeignKeys
     */
    public function testCreateFromRaw(
        array $foreignKeyExpectations,
        array $foreignKeyColumnExpectations,
        array $rawForeignKeys
    ) {
        $mockForeignKeys = $mockForeignKeyColumns = $mockForeignKeyColumnsByForeignKey = [];
        $newForeignKeyArgs = $newForeignKeyColumnArgs = [];

        foreach ($foreignKeyColumnExpectations as $fkName => $fkColumns) {
            foreach ($fkColumns as $column) {
                $newForeignKeyColumnArgs[] = [
                    $column['columnName'],
                    $column['referenceColumnName']
                ];
                $mockForeignKeyColumn = $this->createMockForeignKeyColumn();
                $mockForeignKeyColumns[] = $mockForeignKeyColumn;
                $mockForeignKeyColumnsByForeignKey[$fkName] = $mockForeignKeyColumn;
            }
        }

        foreach ($foreignKeyExpectations as $foreignKey) {
            $newForeignKeyArgs[] = [
                $foreignKey['keyName']
            ];
            $mockForeignKeys[] = $this->createMockForeignKey(
                $foreignKey['tableName'],
                $foreignKey['referenceTableName']
            );
        }

        $mockForeignKeyMapper = $this->createMockForeignKeyMapper(
            $newForeignKeyArgs,
            $mockForeignKeys,
            $newForeignKeyColumnArgs,
            $mockForeignKeyColumns
        );

        $mockForeignKeyMapper->createFromRaw($rawForeignKeys);
    }

    public function testFetchForTable()
    {
        $databaseName = 'schnoop_do';
        $tableName = 'schnoop_tbl';

        $raw = ['foo'];

        $mockForeignKey = $this->createMock(ForeignKey::class);

        /** @var ForeignKeyMapper|PHPUnit_Framework_MockObject_MockObject $mockForeignKeyMapper */
        $mockForeignKeyMapper = $this->getMockBuilder(ForeignKeyMapper::class)
            ->disableOriginalConstructor()
            ->setMethods(['fetchRawForTable', 'createFromRaw'])
            ->getMock();

        $mockForeignKeyMapper->expects($this->once())
            ->method('fetchRawForTable')
            ->with($databaseName, $tableName)
            ->willReturn($raw);

        $mockForeignKeyMapper->expects($this->once())
            ->method('createFromRaw')
            ->with($raw)
            ->willReturn($mockForeignKey);

        $this->assertSame(
            $mockForeignKey,
            $mockForeignKeyMapper->fetchForTable($databaseName, $tableName)
        );
    }

    /**
     * @see testCreateFromRaw
     * @return array
     */
    public function createFromRawTestData()
    {
        return [
            'Create 1 FK with 2 column' => [
                [
                    [
                        'keyName' => 'fk_schnoop',
                        'tableName' => 'schnoop_tbl',
                        'referenceTableName' => 'schnoop_ref_tbl'
                    ]
                ],
                [
                    'fk_schnoop' => [
                        [
                            'columnName' => 'id',
                            'referenceColumnName' => 'ref_id'
                        ],
                        [
                            'columnName' => 'name',
                            'referenceColumnName' => 'ref_name'
                        ],
                    ]
                ],
                [
                    [
                        'table_name' => 'schnoop_tbl',
                        'constraint_name' => 'fk_schnoop',
                        'column_name' => 'id',
                        'ordinal_position' => '1',
                        'referenced_table_name' => 'schnoop_ref_tbl',
                        'referenced_column_name' => 'ref_id'
                    ],
                    [
                        'table_name' => 'schnoop_tbl',
                        'constraint_name' => 'fk_schnoop',
                        'column_name' => 'name',
                        'ordinal_position' => '2',
                        'referenced_table_name' => 'schnoop_ref_tbl',
                        'referenced_column_name' => 'ref_name'
                    ]
                ]
            ],
            'Create 2 FK with 1 column each' => [
                [
                    [
                        'keyName' => 'fk_schnoop01',
                        'tableName' => 'schnoop_tbl',
                        'referenceTableName' => 'schnoop_ref_tbl'
                    ],
                    [
                        'keyName' => 'fk_schnoop02',
                        'tableName' => 'schnoop_tbl',
                        'referenceTableName' => 'schnoop_ref_tbl'
                    ]
                ],
                [
                    'fk_schnoop01' => [
                        [
                            'columnName' => 'id',
                            'referenceColumnName' => 'ref_id'
                        ],
                    ],
                    'fk_schnoop02' => [
                        [
                            'columnName' => 'name',
                            'referenceColumnName' => 'ref_name'
                        ],
                    ]
                ],
                [
                    [
                        'table_name' => 'schnoop_tbl',
                        'constraint_name' => 'fk_schnoop01',
                        'column_name' => 'id',
                        'ordinal_position' => '1',
                        'referenced_table_name' => 'schnoop_ref_tbl',
                        'referenced_column_name' => 'ref_id'
                    ],
                    [
                        'table_name' => 'schnoop_tbl',
                        'constraint_name' => 'fk_schnoop02',
                        'column_name' => 'name',
                        'ordinal_position' => '1',
                        'referenced_table_name' => 'schnoop_ref_tbl',
                        'referenced_column_name' => 'ref_name'
                    ]
                ]
            ]
        ];
    }

    /**
     * @param array $newForeignKeyArgs
     * @param ForeignKeyInterface[] $foreignKeys
     * @param array $newForeignKeyColumnArgs
     * @param ForeignKeyColumnInterface[] $foreignKeyColumns
     * @return ForeignKeyMapper|PHPUnit_Framework_MockObject_MockObject
     */
    protected function createMockForeignKeyMapper(
        array $newForeignKeyArgs,
        array $foreignKeys,
        array $newForeignKeyColumnArgs,
        array $foreignKeyColumns
    ) {
        $mockForeignKeyMapper = $this->getMockBuilder(ForeignKeyMapper::class)
            ->disableOriginalConstructor()
            ->setMethods(['newForeignKey', 'newForeignKeyColumn'])
            ->getMock();
        $mockForeignKeyMapper->expects($this->exactly(count($newForeignKeyArgs)))
            ->method('newForeignKey')
            ->withConsecutive(...$newForeignKeyArgs)
            ->will($this->onConsecutiveCalls(...$foreignKeys));
        $mockForeignKeyMapper->expects($this->exactly(count($newForeignKeyColumnArgs)))
            ->method('newForeignKeyColumn')
            ->withConsecutive(...$newForeignKeyColumnArgs)
            ->will($this->onConsecutiveCalls(...$foreignKeyColumns));

        return $mockForeignKeyMapper;
    }

    /**
     * @param string $expectedTableName
     * @param string $expectedReferenceTableName
     * @return ForeignKeyInterface|PHPUnit_Framework_MockObject_MockObject
     */
    protected function createMockForeignKey($expectedTableName, $expectedReferenceTableName)
    {
        $mockForeignKey = $this->createMock(ForeignKeyInterface::class);
        $mockForeignKey->expects($this->once())
            ->method('setTableName')
            ->with($expectedTableName);
        $mockForeignKey->expects($this->once())
            ->method('setReferenceTableName')
            ->with($expectedReferenceTableName);

        return $mockForeignKey;
    }

    /**
     * @return ForeignKeyColumnInterface|PHPUnit_Framework_MockObject_MockObject
     */
    protected function createMockForeignKeyColumn()
    {
        $mockForeignKeyColumn = $this->createMock(ForeignKeyColumnInterface::class);

        return $mockForeignKeyColumn;
    }
}
