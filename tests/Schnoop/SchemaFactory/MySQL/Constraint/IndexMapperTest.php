<?php

namespace MilesAsylum\Schnoop\Tests\Schnoop\SchemaFactory\MySQL\Constraint;

use MilesAsylum\Schnoop\PHPUnit\Framework\TestMySQLCase;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\Constraint\IndexMapper;
use MilesAsylum\SchnoopSchema\MySQL\Constraint\FullTextIndex;
use MilesAsylum\SchnoopSchema\MySQL\Constraint\Index;
use MilesAsylum\SchnoopSchema\MySQL\Constraint\IndexedColumn;
use MilesAsylum\SchnoopSchema\MySQL\Constraint\IndexedColumnInterface;
use MilesAsylum\SchnoopSchema\MySQL\Constraint\IndexInterface;
use MilesAsylum\SchnoopSchema\MySQL\Constraint\PrimaryKey;
use MilesAsylum\SchnoopSchema\MySQL\Constraint\SpatialIndex;
use MilesAsylum\SchnoopSchema\MySQL\Constraint\UniqueIndex;
use PHPUnit_Framework_MockObject_MockObject;

class IndexMapperTest extends TestMySQLCase
{
    /**
     * @var IndexMapper
     */
    protected $indexMapper;

    protected $tableName = 'schnoop_tbl';

    protected $databaseName;

    public function setUp()
    {
        parent::setUp();

        $this->databaseName = $this->getDatabaseName();

        $this->getConnection()->query(<<<SQL
DROP TABLE IF EXISTS `{$this->databaseName}`.`{$this->tableName}` 
SQL
        );

        $this->getConnection()->query(<<<SQL
CREATE TABLE `{$this->databaseName}`.`{$this->tableName}` (
  id INTEGER,
  name VARCHAR(100)
) ENGINE MyISAM
SQL
        );

        $this->indexMapper = new IndexMapper($this->getConnection());
    }

    /**
     * @dataProvider newIndexProvider
     * @param $indexType
     * @param $indexName
     * @param $expectedInstanceOf
     */
    public function testNewIndex($indexType, $indexName, $expectedInstanceOf)
    {
        $index = $this->indexMapper->newIndex($indexType, $indexName);

        $this->assertSame($indexName, $index->getName());
        $this->assertInstanceOf($expectedInstanceOf, $index);
    }

    public function testNewIndexedColumn()
    {
        $columnName = 'schnoop_col';
        $indexedColumn = $this->indexMapper->newIndexedColumn($columnName);

        $this->assertInstanceOf(IndexedColumn::class, $indexedColumn);
        $this->assertSame($columnName, $indexedColumn->getColumnName());
    }

    /**
     * @dataProvider rawIndexProvider
     * @param string $alterDDL
     * @param array $expectedRawIndexes
     */
    public function testFetchRawForTable($alterDDL, array $expectedRawIndexes)
    {
        $alterDDL = sprintf($alterDDL, $this->databaseName, $this->tableName);
        $this->getConnection()->query($alterDDL);

        $rawIndexes = $this->indexMapper->fetchRawForTable($this->databaseName, $this->tableName);

        $this->assertSame($expectedRawIndexes, $rawIndexes);
    }

    /**
     * @dataProvider createIndexFromRawTestData
     * @param array $indexExpectations
     * @param array $indexedColumnExpectations
     * @param array $rawForTable
     */
    public function testCreateIndexFromRaw(
        array $indexExpectations,
        array $indexedColumnExpectations,
        array $rawForTable
    ) {
        $mockIndexes = $mockIndexedColumns = $mockIndexedColumnsByIndex = [];
        $newIndexArgs = $newIndexedColumnArgs = [];

        foreach ($indexedColumnExpectations as $indexName => $indexedColumns) {
            foreach ($indexedColumns as $column) {
                $newIndexedColumnArgs[] = [$column['columnName']];
                $mockIndexedColumn = $this->createMockIndexedColumn($column['length']);
                $mockIndexedColumns[] = $mockIndexedColumn;
                $mockIndexedColumnsByIndex[$indexName][] = $mockIndexedColumn;
            }
        }

        foreach ($indexExpectations as $index) {
            $newIndexArgs[] = [
                $index['indexType'],
                $index['indexName']
            ];
            $mockIndexes[] = $this->createMockIndex(
                $index['comment'],
                $mockIndexedColumnsByIndex[$index['indexName']]
            );
        }

        /** @var IndexMapper|PHPUnit_Framework_MockObject_MockObject $mockIndexMapper */
        $mockIndexMapper = $this->createMockIndexMapper(
            $newIndexArgs,
            $mockIndexes,
            $newIndexedColumnArgs,
            $mockIndexedColumns
        );

        $mockIndexMapper->createFromRaw($rawForTable);
    }

    /**
     * @return array
     */
    public function rawIndexProvider()
    {
        return [
            [
                <<<SQL
ALTER TABLE `%s`.`%s`
  ADD UNIQUE INDEX ux_name (name(3)) COMMENT 'Unique index comment.',
  ADD INDEX idx_id_name (id, name) COMMENT 'Index comment.'
SQL
                ,
                [
                    [
                        'Table' => $this->tableName,
                        'Non_unique' => '0',
                        'Key_name' => 'ux_name',
                        'Seq_in_index' => '1',
                        'Column_name' => 'name',
                        'Sub_part' => '3',
                        'Index_type' => 'BTREE',
                           'Index_comment' => 'Unique index comment.'
                    ],
                    [
                        'Table' => $this->tableName,
                        'Non_unique' => '1',
                        'Key_name' => 'idx_id_name',
                        'Seq_in_index' => '1',
                        'Column_name' => 'id',
                        'Sub_part' => null,
                        'Index_type' => 'BTREE',
                        'Index_comment' => 'Index comment.'
                    ],
                    [
                        'Table' => $this->tableName,
                        'Non_unique' => '1',
                        'Key_name' => 'idx_id_name',
                        'Seq_in_index' => '2',
                        'Column_name' => 'name',
                        'Sub_part' => null,
                        'Index_type' => 'BTREE',
                        'Index_comment' => 'Index comment.'
                    ]
                ]
            ]
        ];
    }

    /**
     * @see testCreateIndexFromRaw
     * @return array
     */
    public function createIndexFromRawTestData()
    {
        return [
            'Create index' => [
                [
                    [
                        'indexName' => 'idx_name',
                        'indexType' => IndexInterface::CONSTRAINT_INDEX,
                        'comment' => 'Index comment.'
                    ]
                ],
                [
                    'idx_name' => [
                        [
                            'columnName' => 'name',
                            'length' => '3'
                        ]
                    ]
                ],
                [
                    [
                        'Table' => $this->tableName,
                        'Non_unique' => '1',
                        'Key_name' => 'idx_name',
                        'Seq_in_index' => '1',
                        'Column_name' => 'name',
                        'Sub_part' => '3',
                        'Index_type' => 'BTREE',
                        'Index_comment' => 'Index comment.'
                    ]
                ]
            ],
            'Create unique index' => [
                [
                    [
                        'indexName' => 'ux_name',
                        'indexType' => IndexInterface::CONSTRAINT_UNIQUE_INDEX,
                        'comment' => 'Unique index comment.'
                    ]
                ],
                [
                    'ux_name' => [
                        [
                            'columnName' => 'name',
                            'length' => '3'
                        ]
                    ]
                ],
                [
                    [
                        'Table' => $this->tableName,
                        'Non_unique' => '0',
                        'Key_name' => 'ux_name',
                        'Seq_in_index' => '1',
                        'Column_name' => 'name',
                        'Sub_part' => '3',
                        'Index_type' => 'BTREE',
                        'Index_comment' => 'Unique index comment.'
                    ]
                ]
            ],
            'Create fulltext index' => [
                [
                    [
                        'indexName' => 'ftx_name',
                        'indexType' => IndexInterface::CONSTRAINT_FULLTEXT_INDEX,
                        'comment' => 'Fulltext index comment.'
                    ]
                ],
                [
                    'ftx_name' => [
                        [
                            'columnName' => 'name',
                            'length' => '3'
                        ]
                    ]
                ],
                [
                    [
                        'Table' => $this->tableName,
                        'Non_unique' => '1',
                        'Key_name' => 'ftx_name',
                        'Seq_in_index' => '1',
                        'Column_name' => 'name',
                        'Sub_part' => '3',
                        'Index_type' => 'FULLTEXT',
                        'Index_comment' => 'Fulltext index comment.'
                    ]
                ]
            ],
            'Create spatial index' => [
                [
                    [
                        'indexName' => 'spx_name',
                        'indexType' => IndexInterface::CONSTRAINT_SPATIAL_INDEX,
                        'comment' => 'Spatial index comment.'
                    ]
                ],
                [
                    'spx_name' => [
                        [
                            'columnName' => 'name',
                            'length' => '3'
                        ]
                    ]
                ],
                [
                    [
                        'Table' => $this->tableName,
                        'Non_unique' => '1',
                        'Key_name' => 'spx_name',
                        'Seq_in_index' => '1',
                        'Column_name' => 'name',
                        'Sub_part' => '3',
                        'Index_type' => 'RTREE',
                        'Index_comment' => 'Spatial index comment.'
                    ]
                ]
            ],
            'Create 1 indexes with 2 columns' => [
                [
                    [
                        'indexName' => 'idx_name',
                        'indexType' => IndexInterface::CONSTRAINT_INDEX,
                        'comment' => 'Index comment.'
                    ]
                ],
                [
                    'idx_name' => [
                        [
                            'columnName' => 'name01',
                            'length' => '2'
                        ],
                        [
                            'columnName' => 'name02',
                            'length' => '3'
                        ]
                    ],
                ],
                [
                    [
                        'Table' => $this->tableName,
                        'Non_unique' => '1',
                        'Key_name' => 'idx_name',
                        'Seq_in_index' => '1',
                        'Column_name' => 'name01',
                        'Sub_part' => '2',
                        'Index_type' => 'BTREE',
                        'Index_comment' => 'Index comment.'
                    ],
                    [
                        'Table' => $this->tableName,
                        'Non_unique' => '1',
                        'Key_name' => 'idx_name',
                        'Seq_in_index' => '2',
                        'Column_name' => 'name02',
                        'Sub_part' => '3',
                        'Index_type' => 'BTREE',
                        'Index_comment' => 'Index comment.'
                    ]
                ]
            ],
            'Create 2 indexes with 1 column each' => [
                [
                    [
                        'indexName' => 'idx_name01',
                        'indexType' => IndexInterface::CONSTRAINT_INDEX,
                        'comment' => 'Index one comment.'
                    ],
                    [
                        'indexName' => 'idx_name02',
                        'indexType' => IndexInterface::CONSTRAINT_INDEX,
                        'comment' => 'Index two comment.'
                    ]
                ],
                [
                    'idx_name01' => [
                        [
                            'columnName' => 'name01',
                            'length' => '2'
                        ]
                    ],
                    'idx_name02' => [
                        [
                            'columnName' => 'name02',
                            'length' => '3'
                        ]
                    ]
                ],
                [
                    [
                        'Table' => $this->tableName,
                        'Non_unique' => '1',
                        'Key_name' => 'idx_name01',
                        'Seq_in_index' => '1',
                        'Column_name' => 'name01',
                        'Sub_part' => '2',
                        'Index_type' => 'BTREE',
                        'Index_comment' => 'Index one comment.'
                    ],
                    [
                        'Table' => $this->tableName,
                        'Non_unique' => '1',
                        'Key_name' => 'idx_name02',
                        'Seq_in_index' => '1',
                        'Column_name' => 'name02',
                        'Sub_part' => '3',
                        'Index_type' => 'BTREE',
                        'Index_comment' => 'Index two comment.'
                    ]
                ]
            ],
        ];
    }

    /**
     * @see testNewIndex
     * @return array
     */
    public function newIndexProvider()
    {
        return [
            [
                IndexInterface::CONSTRAINT_INDEX,
                'schnoop_idx',
                Index::class
            ],
            [
                IndexInterface::CONSTRAINT_UNIQUE_INDEX,
                'schnoop_idx',
                UniqueIndex::class
            ],
            [
                IndexInterface::CONSTRAINT_FULLTEXT_INDEX,
                'schnoop_idx',
                FullTextIndex::class
            ],
            [
                IndexInterface::CONSTRAINT_SPATIAL_INDEX,
                'schnoop_idx',
                SpatialIndex::class
            ],
            [
                IndexInterface::CONSTRAINT_UNIQUE_INDEX,
                'primary',
                PrimaryKey::class
            ]
        ];
    }

    /**
     * @param array $newIndexArgs
     * @param IndexInterface[] $indexes
     * @param array $newIndexedColumnArgs
     * @param IndexedColumnInterface[] $indexedColumns
     * @return IndexMapper|PHPUnit_Framework_MockObject_MockObject
     */
    protected function createMockIndexMapper(
        array $newIndexArgs,
        array $indexes,
        array $newIndexedColumnArgs,
        array $indexedColumns
    ) {
        /** @var IndexMapper|PHPUnit_Framework_MockObject_MockObject $mockIndexMapper */
        $mockIndexMapper = $this->getMockBuilder(IndexMapper::class)
            ->disableOriginalConstructor()
            ->setMethods(['newIndex', 'newIndexedColumn'])
            ->getMock();
        $mockIndexMapper->expects($this->exactly(count($newIndexArgs)))
            ->method('newIndex')
            ->withConsecutive(...$newIndexArgs)
            ->will($this->onConsecutiveCalls(...$indexes));
        $mockIndexMapper->expects($this->exactly(count($newIndexedColumnArgs)))
            ->method('newIndexedColumn')
            ->withConsecutive(...$newIndexedColumnArgs)
            ->will($this->onConsecutiveCalls(...$indexedColumns));

        return $mockIndexMapper;
    }

    /**
     * @param $expectedComment
     * @param IndexedColumnInterface[] $indexedColumns
     * @return IndexInterface|PHPUnit_Framework_MockObject_MockObject
     */
    protected function createMockIndex($expectedComment, array $indexedColumns)
    {
        /** @var IndexInterface|PHPUnit_Framework_MockObject_MockObject $mockIndex */
        $mockIndex = $this->createMock(IndexInterface::class);
        $mockIndex->expects($this->once())
            ->method('setComment')
            ->with($expectedComment);
        $mockIndex->expects($this->once())
            ->method('setTableName')
            ->with($this->tableName);
        $mockIndex->expects($this->once())
            ->method('setIndexedColumns')
            ->willReturn($indexedColumns);

        return $mockIndex;
    }

    /**
     * @param int $expectedLength
     * @return IndexedColumnInterface|PHPUnit_Framework_MockObject_MockObject
     */
    protected function createMockIndexedColumn($expectedLength)
    {
        /** @var IndexedColumnInterface|PHPUnit_Framework_MockObject_MockObject $mockIndexedColumn */
        $mockIndexedColumn = $this->createMock(IndexedColumnInterface::class);
        $mockIndexedColumn->expects($this->once())
            ->method('setLength')
            ->with($expectedLength);

        return $mockIndexedColumn;
    }
}
