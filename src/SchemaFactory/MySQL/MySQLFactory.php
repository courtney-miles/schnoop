<?php

namespace MilesAsylum\Schnoop\SchemaFactory\MySQL;

use MilesAsylum\Schnoop\Exception\SchnoopException;
use MilesAsylum\Schnoop\Schema\Exception\FactoryException;
use MilesAsylum\Schnoop\Schema\FactoryInterface;
use MilesAsylum\Schnoop\Schema\MySQL;
use MilesAsylum\Schnoop\Schema\MySQL\Column\Column;
use MilesAsylum\Schnoop\Schema\MySQL\Column\ColumnInterface;
use MilesAsylum\Schnoop\Schema\MySQL\Database\Database;
use MilesAsylum\Schnoop\Schema\MySQL\DataType\IntTypeInterface;
use MilesAsylum\Schnoop\Schema\MySQL\DataType\NumericPointTypeInterface;
use MilesAsylum\Schnoop\Schema\MySQL\DataType\NumericTypeInterface;
use MilesAsylum\Schnoop\Schema\MySQL\DataType\OptionsTypeInterface;
use MilesAsylum\Schnoop\Schema\MySQL\DataType\StringTypeInterface;
use MilesAsylum\Schnoop\Schema\MySQL\Index\FullTextIndex;
use MilesAsylum\Schnoop\Schema\MySQL\Index\Index;
use MilesAsylum\Schnoop\Schema\MySQL\Index\IndexedColumn;
use MilesAsylum\Schnoop\Schema\MySQL\Index\IndexedColumnInterface;
use MilesAsylum\Schnoop\Schema\MySQL\Index\IndexInterface;
use MilesAsylum\Schnoop\Schema\MySQL\Index\SpatialIndex;
use MilesAsylum\Schnoop\Schema\MySQL\Index\UniqueIndex;
use MilesAsylum\Schnoop\Schema\MySQL\Table\Table;
use MilesAsylum\Schnoop\Schnoop;

class MySQLFactory implements FactoryInterface
{
    /**
     * @var \PDO
     */
    protected $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function createDatabase(array $rawDatabase)
    {
        return new Database(
            $rawDatabase['name'],
            $rawDatabase['collation_database']
        );
    }
    
    public function createTable(array $rawTable, array $rawColumns, array $rawIndexes)
    {
        $columns = [];
        
        foreach ($rawColumns as $rawCol) {
            $columns[] = $this->createColumn($rawCol);
        }
        
        $table = new Table(
            $rawTable['name'],
            $columns,
            $this->createIndexes($rawIndexes, $columns),
            $rawTable['engine'],
            $rawTable['row_format'],
            $rawTable['collation'],
            $rawTable['comment']
        );
        
        return $table;
    }

    /**
     * @param array $rowColumn
     * @return ColumnInterface
     */
    public function createColumn(array $rowColumn)
    {
        $dataType = $this->createDataType($rowColumn['type'], $rowColumn['collation']);
        $allowNull = strtolower($rowColumn['null']) == 'yes' ? true : false;

        $zeroFill = $autoIncrement = null;

        if ($dataType instanceof NumericTypeInterface) {
            $zeroFill = stripos($rowColumn['type'], 'zerofill') !== false;
            $autoIncrement = strtolower($rowColumn['extra']) == 'auto_increment' ? true: false;
        }

        $column = new Column(
            $rowColumn['field'],
            $dataType,
            $allowNull,
            $rowColumn['default'],
            $rowColumn['comment'],
            $zeroFill,
            $autoIncrement
        );
        
        return $column;
    }

    /**
     * @param $dataTypeString
     * @param null $collation
     * @return IntTypeInterface|NumericPointTypeInterface|OptionsTypeInterface|StringTypeInterface|null
     * @throws FactoryException
     */
    public function createDataType($dataTypeString, $collation = null)
    {
        $dataType = null;

        if (preg_match('/^(\w+)/', $dataTypeString, $matches)) {
            $namespace = 'MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType\\';
            $factoryClass = $namespace . ucfirst(strtolower($matches[1])) . 'TypeFactory';

            if (class_exists($factoryClass)) {
                return $factoryClass::create($dataTypeString, $collation);
            } else {
                throw new FactoryException("A factory class was not found for the {$matches[1]} data type.");
            }
        }

        return $dataType;
    }

    /**
     * @param array $rawIndexes
     * @param ColumnInterface[] $columns
     * @return MySQL\Index\Index[]
     * @throws SchnoopException
     */
    public function createIndexes(array $rawIndexes, array $columns)
    {
        $aggrIndexes = [];
        $indexes = [];

        foreach ($rawIndexes as $rawIndex) {
            $indexName = $rawIndex['key_name'];
            $aggrIndexes[$indexName]['index_type'] = $rawIndex['index_type'];
            $aggrIndexes[$indexName]['non_unique'] = $rawIndex['non_unique'];
            $aggrIndexes[$indexName]['index_comment'] = $rawIndex['index_comment'];

            foreach ($columns as $column) {
                if ($column->getName() == $rawIndex['column_name']) {
                    $aggrIndexes[$indexName]['columns'][$rawIndex['seq_in_index']] = $this->createIndexedColumn(
                        $rawIndex,
                        $column
                    );

                    break;
                }
            }

            if (empty($aggrIndexes[$indexName]['columns'][$rawIndex['seq_in_index']])) {
                throw new SchnoopException(
                    sprintf(
                        'A column named %s is needed for index %s but was not supplied.',
                        $rawIndex['column_name'],
                        $indexName
                    )
                );
            }
        }

        foreach ($aggrIndexes as $keyName => $aggrIndex) {
            if ($aggrIndex['non_unique'] == 0) {
                $indexes[] = new UniqueIndex(
                    $keyName,
                    $aggrIndex['columns'],
                    strtoupper($aggrIndex['index_type']),
                    $aggrIndex['index_comment']
                );
            } else {
                switch (strtoupper($aggrIndex['index_type'])) {
                    case IndexInterface::INDEX_TYPE_FULLTEXT:
                        $indexes[] = new FullTextIndex(
                            $keyName,
                            $aggrIndex['columns'],
                            $aggrIndex['index_comment']
                        );
                        break;
                    case IndexInterface::INDEX_TYPE_RTREE:
                        $indexes[] = new SpatialIndex(
                            $keyName,
                            $aggrIndex['columns'],
                            $aggrIndex['index_comment']
                        );
                        break;
                    default:
                        $indexes[] = new Index(
                            $keyName,
                            $aggrIndex['columns'],
                            $aggrIndex['index_type'],
                            $aggrIndex['index_comment']
                        );
                        break;
                }
            }
        }

        return $indexes;
    }

    public function createIndexedColumn($rawIndex, ColumnInterface $column)
    {
        if ($rawIndex['column_name'] != $column->getName()) {
            throw new SchnoopException(
                sprintf(
                    "Supplied column does not match the index.  Column name is %s but index is for %s.",
                    $column->getName(),
                    $rawIndex['column_name']
                )
            );
        }

        return new IndexedColumn($column, $rawIndex['sub_part'], IndexedColumnInterface::COLLATION_ASC);
    }
}
