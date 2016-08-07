<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 8/06/16
 * Time: 7:57 AM
 */

namespace MilesAsylum\Schnoop\Tests\Schnoop\SchemaFactory\MySQL;

use MilesAsylum\Schnoop\Exception\SchnoopException;
use MilesAsylum\Schnoop\PHPUnit\Framework\SchnoopTestCase;
use MilesAsylum\Schnoop\PHPUnit\Schnoop\MockPdo;
use MilesAsylum\Schnoop\Schema\MySQL\Column\ColumnInterface;
use MilesAsylum\Schnoop\Schema\MySQL\Database\Database;
use MilesAsylum\Schnoop\Schema\MySQL\DataType\CharType;
use MilesAsylum\Schnoop\Schema\MySQL\DataType\DataTypeInterface;
use MilesAsylum\Schnoop\Schema\MySQL\Index\FullTextIndex;
use MilesAsylum\Schnoop\Schema\MySQL\Index\Index;
use MilesAsylum\Schnoop\Schema\MySQL\Index\IndexedColumn;
use MilesAsylum\Schnoop\Schema\MySQL\Index\IndexedColumnInterface;
use MilesAsylum\Schnoop\Schema\MySQL\Index\IndexInterface;
use MilesAsylum\Schnoop\Schema\MySQL\Index\SpatialIndex;
use MilesAsylum\Schnoop\Schema\MySQL\Index\UniqueIndex;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\MySQLFactory;
use MilesAsylum\Schnoop\Schnoop;
use PHPUnit_Framework_MockObject_MockObject;

class MySQLFactoryTest extends SchnoopTestCase
{
    /**
     * @var MySQLFactory
     */
    protected $mysqlFactory;

    /**
     * @var \PDO|PHPUnit_Framework_MockObject_MockObject
     */
    protected $mockPdo;

    /**
     * @var \PDOStatement|PHPUnit_Framework_MockObject_MockObject
     */
    protected $mockPdoStmt;
    
    public function setUp()
    {
        parent::setUp();

        $this->mockPdo = $this->createMock(MockPdo::class);
        $this->mockPdoStmt = $this->createMock(\PDOStatement::class);

        $this->mockPdo->method('prepare')
            ->willReturn($this->mockPdoStmt);

        $this->mysqlFactory = new MySQLFactory($this->mockPdo);
    }
    
    public function testNewDatabase()
    {
        /** @var Schnoop|PHPUnit_Framework_MockObject_MockObject $mockSchnoop */
        $mockSchnoop = $this->createMock(Schnoop::class);
        $mockSchnoop->method('getTableList')
            ->willReturn([]);

        $rawDatabaseData = [
            'name' => 'schnoop',
            'collation_database' => 'utf8mb4_unicode_ci'
        ];

        $this->assertInstanceOf(
            Database::class,
            $this->mysqlFactory->createDatabase($rawDatabaseData, $mockSchnoop)
        );
    }

    public function testCreateDataType()
    {
        $collation = 'utf8_general_ci';
        $charType = $this->mysqlFactory->createDataType('char(23)', $collation);

        $this->assertInstanceOf(CharType::class, $charType);
        $this->assertSame($collation, $charType->getCollation());
    }

    /**
     * @expectedException \MilesAsylum\Schnoop\Schema\Exception\FactoryException
     */
    public function testExceptionOnCreateUnknownType()
    {
        $bogusType = $this->mysqlFactory->createDataType('bogus(23)', 'utf8_general_ci');
    }

    public function testCreateColumn()
    {
        $rawColumn =             [
            'field' => 'schnoop_col',
            'type' => 'CHAR(3)',
            'collation' => 'utf8_general_ci',
            'null' => 'YES',
            'default' => '123',
            'extra' => null,
            'comment' => 'Schnoop comment'
        ];

        $mockDataType = $this->createMock(DataTypeInterface::class);
        $mockDataType->method('doesAllowDefault')->willReturn(true);
        $mockDataType->method('cast')
            ->with($rawColumn['default'])
            ->willReturn((int)$rawColumn['default']);

        /** @var \MilesAsylum\Schnoop\SchemaFactory\MySQL\MySQLFactory|PHPUnit_Framework_MockObject_MockObject $mysqlFactory */
        $mysqlFactory = $this->getMockBuilder(MySQLFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['createDataType'])
            ->getMock();

        $mysqlFactory->expects($this->once())
            ->method('createDataType')
            ->with($rawColumn['type'], $rawColumn['collation'])
            ->willReturn($mockDataType);

        $column = $mysqlFactory->createColumn($rawColumn);

        $this->assertInstanceOf(ColumnInterface::class, $column);
        $this->assertSame($rawColumn['field'], $column->getName());
        $this->assertSame($mockDataType, $column->getDataType());
        $this->assertSame(true, $column->doesAllowNull());
        $this->assertSame((int)$rawColumn['default'], $column->getDefault());
        $this->assertSame($rawColumn['comment'], $column->getComment());
    }

    /**
     * @dataProvider createIndexedColumnProvider
     * @param ColumnInterface $column
     * @param bool $hasLength
     * @param int|null $length
     */
    public function testCreateIndexedColumn($column, $hasLength, $length)
    {
        $indexedColumn = $this->mysqlFactory->createIndexedColumn(
            [
                'column_name' => $column->getName(),
                'sub_part' => $length,
                'collation' => 'A'
            ],
            $column
        );

        $this->assertInstanceOf(IndexedColumn::class, $indexedColumn);
        $this->assertSame($column, $indexedColumn->getColumn());
        $this->assertSame($hasLength, $indexedColumn->hasLength());
        $this->assertSame($length, $indexedColumn->getLength());
        $this->assertSame(IndexedColumnInterface::COLLATION_ASC, $indexedColumn->getCollation());
    }

    /**
     * @expectedException \MilesAsylum\Schnoop\Exception\SchnoopException
     */
    public function testCreateIndexedColumnExceptionOnColumnMismatch()
    {
        $columnName = 'schnoop_col';
        $indexColumnName = 'not_schnoop_col';

        $mockColumn = $this->createMock(ColumnInterface::class);
        $mockColumn->method('getName')->willReturn($columnName);

        $this->mysqlFactory->createIndexedColumn(
            [
                'column_name' => $indexColumnName,
                'sub_part' => null,
                'collation' => 'A'
            ],
            $mockColumn
        );
    }

    /**
     * @dataProvider createIndexesProvider
     * @param array $rawIndexes
     * @param array $columnNames
     * @param array $expectations
     */
    public function testCreateIndexes(array $rawIndexes, array $expectations)
    {
        $mockIndexedColumn = $this->createMock(IndexedColumnInterface::class);

        $mysqlFactory = $this->getMockBuilder(MySQLFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['createIndexedColumn'])
            ->getMock();

        $indexedColumnReturnMap = [];
        $columns = [];

        foreach ($rawIndexes as $k => $rawIndex) {
            $mockCol = $this->createMockColumn($rawIndex['column_name']);
            $columns[] = $mockCol;
            $indexedColumnReturnMap[] = array(
                $rawIndex,
                $mockCol,
                $mockIndexedColumn
            );
        }

        $mysqlFactory->method('createIndexedColumn')
            ->will(
                $this->returnValueMap($indexedColumnReturnMap)
            );

        $indexes = $this->mysqlFactory->createIndexes(
            $rawIndexes,
            $columns
        );

        $this->assertCount(count($expectations), $indexes);

        for ($i = 0; $i < count($expectations); $i++) {
            $this->assertInstanceOf($expectations[$i]['instanceOf'], $indexes[$i]);
            $this->assertSame($expectations[$i]['name'], $indexes[$i]->getName());
            $this->assertSame($expectations[$i]['indexType'], $indexes[$i]->getIndexType());
            $this->assertCount($expectations[$i]['countColumns'], $indexes[$i]->getIndexedColumns());
            $this->assertSame($expectations[$i]['comment'], $indexes[$i]->getComment());
        }
    }

    /**
     * @expectedException \MilesAsylum\Schnoop\Exception\SchnoopException
     */
    public function testExceptionWhenMissingColumnForIndex()
    {
        $this->mysqlFactory->createIndexes(
            [
                [
                    'non_unique' => '0',
                    'key_name' => 'schnoop_idx',
                    'seq_in_index' => 1,
                    'column_name' => 'schnoop_col1',
                    'collation' => 'A',
                    'sub_part' => null,
                    'index_type' => 'BTREE',
                    'index_comment' => 'Index Comment'
                ]
            ],
            []
        );
    }

    public function testCreateTable()
    {
        $name = 'schnoop_tbl';
        $engine = 'InnoDB';
        $rowFormat = 'compact';
        $defaultCollation = 'utf8_general_ci';
        $comment = 'Schnoop comment';
        $columnName = 'schnoop_col';

        $rawColumns = [
            [$columnName]
        ];
        $rawIndexes = ['foo_indexes'];

        $mockIndex = $this->createMock(IndexInterface::class);
        $mockColumn = $this->createMock(ColumnInterface::class);
        $mockColumn->method('getName')->willReturn($columnName);

        /** @var \MilesAsylum\Schnoop\SchemaFactory\MySQL\MySQLFactory|PHPUnit_Framework_MockObject_MockObject $mysqlFactory */
        $mysqlFactory = $this->getMockBuilder(MySQLFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(
                [
                    'createColumn',
                    'createIndexes'
                ]
            )
            ->getMock();
        $mysqlFactory->method('createColumn')
            ->with($rawColumns[0])
            ->willReturn($mockColumn);
        $mysqlFactory->method('createIndexes')
            ->with($rawIndexes, [$mockColumn])
            ->willReturn(
                [$mockIndex]
            );

        $table = $mysqlFactory->createTable(
            [
                'name' => $name,
                'engine' => $engine,
                'row_format' => $rowFormat,
                'collation' => $defaultCollation,
                'comment' => $comment
            ],
            $rawColumns,
            $rawIndexes
        );

        $this->assertSame($name, $table->getName());
        $this->assertSame($engine, $table->getEngine());
        $this->assertSame($rowFormat, $table->getRowFormat());
        $this->assertSame($defaultCollation, $table->getDefaultCollation());
        $this->assertSame($comment, $table->getComment());

        $this->assertSame(
            $mockColumn,
            $table->getColumn($columnName)
        );
    }

    /**
     * @see testCreateIndexedColumn
     * @return array
     */
    public function createIndexedColumnProvider()
    {
        $mockColumn = $this->createMock('\MilesAsylum\Schnoop\Schema\MySQL\Column\ColumnInterface');
        $mockColumn->method('getName')->willReturn('schnoop_col');

        return [
            [
                $mockColumn,
                false,
                null
            ],
            [
                $mockColumn,
                true,
                123
            ]
        ];
    }

    /**
     * @see testCreateIndexes
     * @return array
     */
    public function createIndexesProvider()
    {
        return [
            [
                [
                    [
                        'non_unique' => '1',
                        'key_name' => 'schnoop_idx',
                        'seq_in_index' => 1,
                        'column_name' => 'schnoop_col1',
                        'collation' => 'A',
                        'sub_part' => null,
                        'index_type' => 'BTREE',
                        'index_comment' => 'Index Comment'
                    ],
                    [
                        'non_unique' => '1',
                        'key_name' => 'schnoop_idx',
                        'seq_in_index' => 2,
                        'column_name' => 'schnoop_col2',
                        'collation' => 'A',
                        'sub_part' => null,
                        'index_type' => 'BTREE',
                        'index_comment' => 'Index Comment'
                    ]
                ],
                [
                    [
                        'instanceOf' => Index::class,
                        'name' => 'schnoop_idx',
                        'indexType' => 'BTREE',
                        'countColumns' => 2,
                        'comment' => 'Index Comment'
                    ]
                ]
            ],
            [
                [
                    [
                        'non_unique' => '0',
                        'key_name' => 'schnoop_idx',
                        'seq_in_index' => 1,
                        'column_name' => 'schnoop_col1',
                        'collation' => 'A',
                        'sub_part' => null,
                        'index_type' => 'BTREE',
                        'index_comment' => 'Index Comment'
                    ],
                    [
                        'non_unique' => '0',
                        'key_name' => 'schnoop_idx',
                        'seq_in_index' => 2,
                        'column_name' => 'schnoop_col2',
                        'collation' => 'A',
                        'sub_part' => null,
                        'index_type' => 'BTREE',
                        'index_comment' => 'Index Comment'
                    ]
                ],
                [
                    [
                        'instanceOf' => UniqueIndex::class,
                        'name' => 'schnoop_idx',
                        'indexType' => 'BTREE',
                        'countColumns' => 2,
                        'comment' => 'Index Comment'
                    ]
                ]
            ],
            [
                [
                    [
                        'non_unique' => '1',
                        'key_name' => 'schnoop_idx',
                        'seq_in_index' => 1,
                        'column_name' => 'schnoop_col1',
                        'collation' => 'A',
                        'sub_part' => null,
                        'index_type' => 'RTREE',
                        'index_comment' => 'Index Comment'
                    ],
                    [
                        'non_unique' => '1',
                        'key_name' => 'schnoop_idx',
                        'seq_in_index' => 2,
                        'column_name' => 'schnoop_col2',
                        'collation' => 'A',
                        'sub_part' => null,
                        'index_type' => 'RTREE',
                        'index_comment' => 'Index Comment'
                    ]
                ],
                [
                    [
                        'instanceOf' => SpatialIndex::class,
                        'name' => 'schnoop_idx',
                        'indexType' => 'RTREE',
                        'countColumns' => 2,
                        'comment' => 'Index Comment'
                    ]
                ]
            ],
            [
                [
                    [
                        'non_unique' => '1',
                        'key_name' => 'schnoop_idx',
                        'seq_in_index' => 1,
                        'column_name' => 'schnoop_col1',
                        'collation' => 'A',
                        'sub_part' => null,
                        'index_type' => 'FULLTEXT',
                        'index_comment' => 'Index Comment'
                    ],
                    [
                        'non_unique' => '1',
                        'key_name' => 'schnoop_idx',
                        'seq_in_index' => 2,
                        'column_name' => 'schnoop_col2',
                        'collation' => 'A',
                        'sub_part' => null,
                        'index_type' => 'FULLTEXT',
                        'index_comment' => 'Index Comment'
                    ]
                ],
                [
                    [
                        'instanceOf' => FullTextIndex::class,
                        'name' => 'schnoop_idx',
                        'indexType' => 'FULLTEXT',
                        'countColumns' => 2,
                        'comment' => 'Index Comment'
                    ]
                ]
            ]
        ];
    }

    /**
     * @param $columnName
     * @return ColumnInterface|PHPUnit_Framework_MockObject_MockObject
     */
    protected function createMockColumn($columnName)
    {
        /** @var ColumnInterface|PHPUnit_Framework_MockObject_MockObject $mockColumn */
        $mockColumn = $this->createMock(ColumnInterface::class);
        $mockColumn->method('getName')->willReturn($columnName);

        return $mockColumn;
    }
}
