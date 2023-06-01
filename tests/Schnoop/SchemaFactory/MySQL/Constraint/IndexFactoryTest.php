<?php

namespace MilesAsylum\Schnoop\Tests\Schnoop\SchemaFactory\MySQL\Constraint;

use MilesAsylum\Schnoop\PHPUnit\Framework\TestMySQLCase;
use MilesAsylum\Schnoop\SchemaFactory\Exception\FactoryException;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\Constraint\IndexFactory;
use MilesAsylum\SchnoopSchema\MySQL\Constraint\FullTextIndex;
use MilesAsylum\SchnoopSchema\MySQL\Constraint\Index;
use MilesAsylum\SchnoopSchema\MySQL\Constraint\IndexedColumn;
use MilesAsylum\SchnoopSchema\MySQL\Constraint\IndexedColumnInterface;
use MilesAsylum\SchnoopSchema\MySQL\Constraint\IndexInterface;
use MilesAsylum\SchnoopSchema\MySQL\Constraint\PrimaryKey;
use MilesAsylum\SchnoopSchema\MySQL\Constraint\SpatialIndex;
use MilesAsylum\SchnoopSchema\MySQL\Constraint\UniqueIndex;
use PHPUnit\Framework\MockObject\MockObject;

class IndexFactoryTest extends TestMySQLCase
{
    /**
     * @var IndexFactory
     */
    protected $indexMapper;

    protected $tableName = 'schnoop_tbl';

    protected $databaseName;

    public function setUp(): void
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

        $this->indexMapper = new IndexFactory($this->getConnection());
    }

    /**
     * @dataProvider newIndexProvider
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
     *
     * @param string $alterDDL
     */
    public function testFetchRaw($alterDDL, array $expectedRawIndexes)
    {
        $alterDDL = sprintf($alterDDL, $this->databaseName, $this->tableName);
        $this->getConnection()->query($alterDDL);

        $rawIndexes = $this->indexMapper->fetchRaw($this->databaseName, $this->tableName);

        $this->assertSame($expectedRawIndexes, $rawIndexes);
    }

    public function testFetch()
    {
        $databaseName = 'schnoop_do';
        $tableName = 'schnoop_tbl';

        $raw = ['foo'];

        $mockIndex = $this->createMock(Index::class);

        /** @var IndexFactory|MockObject $mockIndexMapper */
        $mockIndexMapper = $this->getMockBuilder(IndexFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['fetchRaw', 'createFromRaw'])
            ->getMock();

        $mockIndexMapper->expects($this->once())
            ->method('fetchRaw')
            ->with($databaseName, $tableName)
            ->willReturn($raw);

        $mockIndexMapper->expects($this->once())
            ->method('createFromRaw')
            ->with($raw)
            ->willReturn($mockIndex);

        $this->assertSame(
            $mockIndex,
            $mockIndexMapper->fetch($tableName, $databaseName)
        );
    }

    /**
     * @dataProvider createIndexFromRawTestData
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
                $index['indexName'],
            ];
            $mockIndexes[] = $this->createMockIndex(
                $index['comment'],
                $mockIndexedColumnsByIndex[$index['indexName']]
            );
        }

        /** @var IndexFactory|MockObject $mockIndexMapper */
        $mockIndexMapper = $this->createMockIndexMapper(
            $newIndexArgs,
            $mockIndexes,
            $newIndexedColumnArgs,
            $mockIndexedColumns
        );

        $mockIndexMapper->createFromRaw($rawForTable);
    }

    public function testExceptionOnNewBogusIndex()
    {
        $this->expectException(FactoryException::class);
        $this->indexMapper->newIndex('bogus', 'foo');
    }

    public function testExceptionOnBogusTypeCreateFromRaw()
    {
        $this->expectExceptionMessage('Unknown index type, bogus.');
        $this->expectException(FactoryException::class);
        $rawIndexes = [
            [
                'key_name' => 'foo',
                'non_unique' => 1,
                'index_type' => 'bogus',
            ],
        ];

        $this->indexMapper->createFromRaw($rawIndexes);
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
                           'Index_comment' => 'Unique index comment.',
                    ],
                    [
                        'Table' => $this->tableName,
                        'Non_unique' => '1',
                        'Key_name' => 'idx_id_name',
                        'Seq_in_index' => '1',
                        'Column_name' => 'id',
                        'Sub_part' => null,
                        'Index_type' => 'BTREE',
                        'Index_comment' => 'Index comment.',
                    ],
                    [
                        'Table' => $this->tableName,
                        'Non_unique' => '1',
                        'Key_name' => 'idx_id_name',
                        'Seq_in_index' => '2',
                        'Column_name' => 'name',
                        'Sub_part' => null,
                        'Index_type' => 'BTREE',
                        'Index_comment' => 'Index comment.',
                    ],
                ],
            ],
        ];
    }

    /**
     * @see testCreateIndexFromRaw
     *
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
                        'comment' => 'Index comment.',
                    ],
                ],
                [
                    'idx_name' => [
                        [
                            'columnName' => 'name',
                            'length' => '3',
                        ],
                    ],
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
                        'Index_comment' => 'Index comment.',
                    ],
                ],
            ],
            'Create unique index' => [
                [
                    [
                        'indexName' => 'ux_name',
                        'indexType' => IndexInterface::CONSTRAINT_INDEX_UNIQUE,
                        'comment' => 'Unique index comment.',
                    ],
                ],
                [
                    'ux_name' => [
                        [
                            'columnName' => 'name',
                            'length' => '3',
                        ],
                    ],
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
                        'Index_comment' => 'Unique index comment.',
                    ],
                ],
            ],
            'Create fulltext index' => [
                [
                    [
                        'indexName' => 'ftx_name',
                        'indexType' => IndexInterface::CONSTRAINT_INDEX_FULLTEXT,
                        'comment' => 'Fulltext index comment.',
                    ],
                ],
                [
                    'ftx_name' => [
                        [
                            'columnName' => 'name',
                            'length' => '3',
                        ],
                    ],
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
                        'Index_comment' => 'Fulltext index comment.',
                    ],
                ],
            ],
            'Create spatial index' => [
                [
                    [
                        'indexName' => 'spx_name',
                        'indexType' => IndexInterface::CONSTRAINT_INDEX_SPATIAL,
                        'comment' => 'Spatial index comment.',
                    ],
                ],
                [
                    'spx_name' => [
                        [
                            'columnName' => 'name',
                            'length' => '3',
                        ],
                    ],
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
                        'Index_comment' => 'Spatial index comment.',
                    ],
                ],
            ],
            'Create 1 indexes with 2 columns' => [
                [
                    [
                        'indexName' => 'idx_name',
                        'indexType' => IndexInterface::CONSTRAINT_INDEX,
                        'comment' => 'Index comment.',
                    ],
                ],
                [
                    'idx_name' => [
                        [
                            'columnName' => 'name01',
                            'length' => '2',
                        ],
                        [
                            'columnName' => 'name02',
                            'length' => '3',
                        ],
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
                        'Index_comment' => 'Index comment.',
                    ],
                    [
                        'Table' => $this->tableName,
                        'Non_unique' => '1',
                        'Key_name' => 'idx_name',
                        'Seq_in_index' => '2',
                        'Column_name' => 'name02',
                        'Sub_part' => '3',
                        'Index_type' => 'BTREE',
                        'Index_comment' => 'Index comment.',
                    ],
                ],
            ],
            'Create 2 indexes with 1 column each' => [
                [
                    [
                        'indexName' => 'idx_name01',
                        'indexType' => IndexInterface::CONSTRAINT_INDEX,
                        'comment' => 'Index one comment.',
                    ],
                    [
                        'indexName' => 'idx_name02',
                        'indexType' => IndexInterface::CONSTRAINT_INDEX,
                        'comment' => 'Index two comment.',
                    ],
                ],
                [
                    'idx_name01' => [
                        [
                            'columnName' => 'name01',
                            'length' => '2',
                        ],
                    ],
                    'idx_name02' => [
                        [
                            'columnName' => 'name02',
                            'length' => '3',
                        ],
                    ],
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
                        'Index_comment' => 'Index one comment.',
                    ],
                    [
                        'Table' => $this->tableName,
                        'Non_unique' => '1',
                        'Key_name' => 'idx_name02',
                        'Seq_in_index' => '1',
                        'Column_name' => 'name02',
                        'Sub_part' => '3',
                        'Index_type' => 'BTREE',
                        'Index_comment' => 'Index two comment.',
                    ],
                ],
            ],
        ];
    }

    /**
     * @see testNewIndex
     *
     * @return array
     */
    public function newIndexProvider()
    {
        return [
            [
                IndexInterface::CONSTRAINT_INDEX,
                'schnoop_idx',
                Index::class,
            ],
            [
                IndexInterface::CONSTRAINT_INDEX_UNIQUE,
                'schnoop_idx',
                UniqueIndex::class,
            ],
            [
                IndexInterface::CONSTRAINT_INDEX_FULLTEXT,
                'schnoop_idx',
                FullTextIndex::class,
            ],
            [
                IndexInterface::CONSTRAINT_INDEX_SPATIAL,
                'schnoop_idx',
                SpatialIndex::class,
            ],
            [
                IndexInterface::CONSTRAINT_INDEX_UNIQUE,
                'primary',
                PrimaryKey::class,
            ],
        ];
    }

    /**
     * @param IndexInterface[]         $indexes
     * @param IndexedColumnInterface[] $indexedColumns
     *
     * @return IndexFactory|MockObject
     */
    protected function createMockIndexMapper(
        array $newIndexArgs,
        array $indexes,
        array $newIndexedColumnArgs,
        array $indexedColumns
    ) {
        /** @var IndexFactory|MockObject $mockIndexMapper */
        $mockIndexMapper = $this->getMockBuilder(IndexFactory::class)
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
     * @param IndexedColumnInterface[] $indexedColumns
     *
     * @return IndexInterface|MockObject
     */
    protected function createMockIndex($expectedComment, array $indexedColumns)
    {
        /** @var IndexInterface|MockObject $mockIndex */
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
     *
     * @return IndexedColumnInterface|MockObject
     */
    protected function createMockIndexedColumn($expectedLength)
    {
        /** @var IndexedColumnInterface|MockObject $mockIndexedColumn */
        $mockIndexedColumn = $this->createMock(IndexedColumnInterface::class);
        $mockIndexedColumn->expects($this->once())
            ->method('setLength')
            ->with($expectedLength);

        return $mockIndexedColumn;
    }
}
