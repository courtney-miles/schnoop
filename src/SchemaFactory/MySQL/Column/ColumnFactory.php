<?php

namespace MilesAsylum\Schnoop\SchemaFactory\MySQL\Column;

use MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType\DataTypeFactory;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType\DataTypeFactoryInterface;
use MilesAsylum\SchnoopSchema\MySQL\Column\Column;
use MilesAsylum\SchnoopSchema\MySQL\DataType\DataTypeInterface;

class ColumnFactory implements ColumnFactoryInterface
{
    /**
     * @var \PDO
     */
    protected $pdo;

    /**
     * @var DataTypeFactory
     */
    protected $dataTypeMapper;

    /**
     * @var string
     */
    protected $sqlShowFullColumns;

    /**
     * ColumnMapper constructor.
     */
    public function __construct(\PDO $pdo, DataTypeFactoryInterface $dataTypeFactory)
    {
        $this->pdo = $pdo;
        $this->dataTypeMapper = $dataTypeFactory;

        $this->sqlShowFullColumns = <<< SQL
SHOW FULL COLUMNS FROM `%s`.`%s`
SQL;
    }

    public function fetch($tableName, $databaseName)
    {
        $columns = [];
        $rows = $this->fetchRaw($databaseName, $tableName);

        foreach ($rows as $row) {
            $column = $this->createFromRaw($row);
            $column->setTableName($tableName);
            $columns[] = $column;
        }

        return $columns;
    }

    public function fetchRaw($databaseName, $tableName)
    {
        $rawColumns = [];

        $rows = $this->pdo->query(
            sprintf(
                $this->sqlShowFullColumns,
                $databaseName,
                $tableName
            )
        )->fetchAll(\PDO::FETCH_ASSOC);

        foreach ($rows as $k => $row) {
            $autoIncrement = ('auto_increment' == $row['Extra']);
            $rawColumns[] = array_intersect_key(
                $row,
                array_fill_keys(
                    [
                        'Field',
                        'Type',
                        'Collation',
                        'Null',
                        'Default',
                        'Extra',
                        'Comment',
                    ],
                    true
                )
            );
        }

        return $rawColumns;
    }

    public function createFromRaw(array $rawColumn)
    {
        $rawColumn = $this->keysToLower($rawColumn);

        $dataType = $this->dataTypeMapper->createType($rawColumn['type'], $rawColumn['collation']);

        $column = $this->newColumn($rawColumn['field'], $dataType);
        $column->setNullable('yes' == strtolower($rawColumn['null']));
        $column->setAutoIncrement('auto_increment' == $rawColumn['extra']);
        $column->setDefault($rawColumn['default']);
        $column->setOnUpdateCurrentTimestamp(0 == strcasecmp($rawColumn['extra'], 'on update CURRENT_TIMESTAMP'));
        $column->setComment($rawColumn['comment']);

        return $column;
    }

    public function newColumn($name, DataTypeInterface $dataType)
    {
        return new Column(
            $name,
            $dataType
        );
    }

    /**
     * Convert associative array keys to lower case.
     *
     * @param array $arr associative array
     *
     * @return array a copy of the supplied array with all keys changed to lower case
     */
    protected function keysToLower(array $arr)
    {
        $keysToLower = [];

        foreach ($arr as $k => $v) {
            $keysToLower[strtolower($k)] = $v;
        }

        return $keysToLower;
    }
}
