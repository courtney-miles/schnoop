<?php

namespace MilesAsylum\Schnoop\SchemaFactory\MySQL\Constraint;

use MilesAsylum\Schnoop\SchemaFactory\Exception\FactoryException;
use MilesAsylum\SchnoopSchema\MySQL\Constraint\FullTextIndex;
use MilesAsylum\SchnoopSchema\MySQL\Constraint\Index;
use MilesAsylum\SchnoopSchema\MySQL\Constraint\IndexedColumn;
use MilesAsylum\SchnoopSchema\MySQL\Constraint\IndexInterface;
use MilesAsylum\SchnoopSchema\MySQL\Constraint\PrimaryKey;
use MilesAsylum\SchnoopSchema\MySQL\Constraint\SpatialIndex;
use MilesAsylum\SchnoopSchema\MySQL\Constraint\UniqueIndex;
use PDO;

class IndexFactory implements IndexFactoryInterface
{
    /**
     * @var IndexFactoryInterface[]
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

    /**
     * IndexFactory constructor.
     */
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;

        $this->sqlShowIndexes = <<< SQL
SHOW INDEXES FROM `%s`.`%s`
SQL;
    }

    /**
     * {@inheritdoc}
     */
    public function fetch($tableName, $databaseName)
    {
        $rows = $this->fetchRaw($databaseName, $tableName);
        $indexes = $this->createFromRaw($rows);

        return $indexes;
    }

    /**
     * {@inheritdoc}
     */
    public function fetchRaw($databaseName, $tableName)
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
                        'Index_comment',
                    ],
                    true
                )
            );

            // Emulate PDO::setAttribute(PDO::ATTR_STRINGIFY_FETCHES, true).
            // We don't want to screw with connection attributes.
            $row = array_map(
                static function ($v) {
                    return null !== $v ? (string) $v : $v;
                },
                $row
            );

            $rawIndexes[$k] = $row;
        }

        return $rawIndexes;
    }

    /**
     * {@inheritdoc}
     *
     * @throws FactoryException
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
                if (0 == $rawTableIndex['non_unique']) {
                    $index = $this->newIndex(IndexInterface::CONSTRAINT_INDEX_UNIQUE, $keyName);
                } else {
                    switch (strtolower($rawTableIndex['index_type'])) {
                        case 'fulltext':
                            $index = $this->newIndex(IndexInterface::CONSTRAINT_INDEX_FULLTEXT, $keyName);
                            break;
                        case 'rtree':
                            $index = $this->newIndex(IndexInterface::CONSTRAINT_INDEX_SPATIAL, $keyName);
                            break;
                        case 'btree':
                        case 'hash':
                            $index = $this->newIndex(IndexInterface::CONSTRAINT_INDEX, $keyName);
                            break;
                        default:
                            throw new FactoryException("Unknown index type, {$rawTableIndex['index_type']}.");
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

    /**
     * @param string $indexType one of IndexInterface::CONSTRAIN_* constants
     * @param string $indexName
     *
     * @return FullTextIndex|Index|PrimaryKey|SpatialIndex|UniqueIndex
     *
     * @throws FactoryException
     */
    public function newIndex($indexType, $indexName)
    {
        switch ($indexType) {
            case IndexInterface::CONSTRAINT_INDEX:
                return new Index($indexName);
                break;
            case IndexInterface::CONSTRAINT_INDEX_UNIQUE:
                if ('primary' == strtolower($indexName)) {
                    return new PrimaryKey($indexName);
                } else {
                    return new UniqueIndex($indexName);
                }
                break;
            case IndexInterface::CONSTRAINT_INDEX_FULLTEXT:
                return new FullTextIndex($indexName);
                break;
            case IndexInterface::CONSTRAINT_INDEX_SPATIAL:
                return new SpatialIndex($indexName);
                break;
            default:
                throw new FactoryException("Unrecognised index, $indexType");
        }
    }

    /**
     * Create a new Indexed Column.
     *
     * @param string $columnName
     *
     * @return IndexedColumn
     */
    public function newIndexedColumn($columnName)
    {
        return new IndexedColumn($columnName);
    }

    /**
     * Convert the associative keys of the supplied array to lower case.
     *
     * @param array $array associative array
     *
     * @return array copy of array with associative keys changed to lower case
     */
    protected function keysToLower(array $array)
    {
        $newArray = [];

        foreach ($array as $k => $v) {
            $newArray[strtolower($k)] = $v;
        }

        return $newArray;
    }
}
