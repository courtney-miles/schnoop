<?php

namespace MilesAsylum\Schnoop\SchemaFactory\MySQL\Constraint;

use MilesAsylum\Schnoop\Exception\SchnoopException;
use MilesAsylum\Schnoop\SchemaFactory\IndexMapperInterface;
use MilesAsylum\SchnoopSchema\MySQL\Constraint\ConstraintInterface;
use MilesAsylum\SchnoopSchema\MySQL\Constraint\FullTextIndex;
use MilesAsylum\SchnoopSchema\MySQL\Constraint\Index;
use MilesAsylum\SchnoopSchema\MySQL\Constraint\IndexedColumn;
use MilesAsylum\SchnoopSchema\MySQL\Constraint\IndexInterface;
use MilesAsylum\SchnoopSchema\MySQL\Constraint\PrimaryKey;
use MilesAsylum\SchnoopSchema\MySQL\Constraint\SpatialIndex;
use MilesAsylum\SchnoopSchema\MySQL\Constraint\UniqueIndex;
use PDO;

class IndexMapper implements IndexMapperInterface
{
    /**
     * @var IndexMapperInterface[]
     */
    protected $mapHandlers = [];
    /**
     * @var PDO
     */
    protected $pdo;

    /**
     * @var string
     */
    protected $sqlShowIndexes;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;

        $this->sqlShowIndexes = <<< SQL
SHOW INDEXES FROM `%s`.`%s`
SQL;
    }

    /**
     * @param $databaseName
     * @param $tableName
     * @return ConstraintInterface[]
     */
    public function fetchForTable($databaseName, $tableName)
    {
        $rows = $this->fetchRawForTable($databaseName, $tableName);
        $indexes = $this->createFromRaw($rows);

        return $indexes;
    }

    public function fetchRawForTable($databaseName, $tableName)
    {
        $rawIndexes = [];

        $rows = $this->pdo->query(
            sprintf(
                $this->sqlShowIndexes,
                $databaseName,
                $tableName
            )
        )->fetchAll(PDO::FETCH_ASSOC);

        foreach ($rows as $k => $row) {
            $row = array_intersect_key(
                $row,
                array_fill_keys(
                    [
                        'Table',
                        'Non_unique',
                        'Key_name',
                        'Seq_in_index',
                        'Column_name',
                        'Sub_part',
                        'Index_type',
                        'Index_comment'
                    ],
                    true
                )
            );
            $rawIndexes[$k] = $row;
        }

        return $rawIndexes;
    }

    /**
     * @param array $rawTableIndexes
     * @return \MilesAsylum\SchnoopSchema\MySQL\Constraint\IndexInterface[]
     * @throws SchnoopException
     */
    public function createFromRaw(array $rawTableIndexes)
    {
        /** @var IndexInterface[] $indexes */
        $indexes = [];
        /** @var IndexedColumn[][] $indexIndexedColumns */
        $indexIndexedColumns = [];

        foreach ($rawTableIndexes as $rawTableIndex) {
            $rawTableIndex = $this->keysToLower($rawTableIndex);
            $keyName = $rawTableIndex['key_name'];

            if (!isset($indexes[$keyName])) {
                if ($rawTableIndex['non_unique'] == 0) {
                    $index = $this->newIndex(IndexInterface::CONSTRAINT_UNIQUE_INDEX, $keyName);
                } else {
                    switch (strtolower($rawTableIndex['index_type'])) {
                        case 'fulltext':
                            $index = $this->newIndex(IndexInterface::CONSTRAINT_FULLTEXT_INDEX, $keyName);
                            break;
                        case 'rtree':
                            $index = $this->newIndex(IndexInterface::CONSTRAINT_SPATIAL_INDEX, $keyName);
                            break;
                        case 'btree':
                        case 'hash':
                            $index = $this->newIndex(IndexInterface::CONSTRAINT_INDEX, $keyName);
                            break;
                        default:
                            throw new SchnoopException("Unknown index type, {$rawTableIndex['index_type']}.");
                    }
                }

                $index->setComment($rawTableIndex['index_comment']);
                $index->setTableName($rawTableIndex['table']);
                $indexes[$keyName] = $index;
            }

            $indexedColumn = $this->newIndexedColumn($rawTableIndex['column_name']);
            $indexedColumn->setLength($rawTableIndex['sub_part']);

            $indexIndexedColumns[$keyName][$rawTableIndex['seq_in_index']] = $indexedColumn;
        }

        foreach ($indexes as $keyName => $index) {
            ksort($indexIndexedColumns[$keyName]);
            $index->setIndexedColumns($indexIndexedColumns[$keyName]);
        }

        return $indexes;
    }

    public function newIndex($indexType, $indexName)
    {
        switch ($indexType) {
            case IndexInterface::CONSTRAINT_INDEX:
                return new Index($indexName);
                break;
            case IndexInterface::CONSTRAINT_UNIQUE_INDEX:
                if (strtolower($indexName) == 'primary') {
                    return new PrimaryKey($indexName);
                } else {
                    return new UniqueIndex($indexName);
                }
                break;
            case IndexInterface::CONSTRAINT_FULLTEXT_INDEX:
                return new FullTextIndex($indexName);
                break;
            case IndexInterface::CONSTRAINT_SPATIAL_INDEX:
                return new SpatialIndex($indexName);
                break;
            default:
                throw new SchnoopException("Unrecognised index, $indexType");
        }
    }

    public function newIndexedColumn($columnName)
    {
        return new IndexedColumn($columnName);
    }

    protected function keysToLower(array $array)
    {
        $newArray = [];

        foreach ($array as $k => $v) {
            $newArray[strtolower($k)] = $v;
        }

        return $newArray;
    }
}
