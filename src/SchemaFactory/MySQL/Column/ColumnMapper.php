<?php

namespace MilesAsylum\Schnoop\SchemaFactory\MySQL\Column;

use MilesAsylum\Schnoop\SchemaFactory\ColumnMapperInterface;
use MilesAsylum\Schnoop\SchemaFactory\DataTypeFactoryInterface;
use MilesAsylum\Schnoop\SchemaFactory\DataTypeFactory;
use MilesAsylum\SchnoopSchema\MySQL\Column\Column;
use MilesAsylum\SchnoopSchema\MySQL\DataType\DataTypeInterface;
use MilesAsylum\SchnoopSchema\MySQL\DataType\NumericTypeInterface;
use PDO;

class ColumnMapper implements ColumnMapperInterface
{
    /**
     * @var PDO
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
     * @param PDO $pdo
     * @param DataTypeFactoryInterface $dataTypeFactory
     */
    public function __construct(PDO $pdo, DataTypeFactoryInterface $dataTypeFactory)
    {
        $this->pdo = $pdo;
        $this->dataTypeMapper = $dataTypeFactory;

        $this->sqlShowFullColumns = <<< SQL
SHOW FULL COLUMNS FROM `%s`.`%s`
SQL;
    }

    /**
     * @param $databaseName
     * @param $tableName
     * @return Column[]
     */
    public function fetch($databaseName, $tableName)
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
        )->fetchAll(PDO::FETCH_ASSOC);

        foreach ($rows as $k => $row) {
            $autoIncrement = ($row['Extra'] == 'auto_increment');
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
                        'Comment'
                    ],
                    true
                )
            );
        }

        return $rawColumns;
    }

    /**
     * @param array $rawColumn
     * @return Column
     */
    public function createFromRaw(array $rawColumn)
    {
        $rawColumn = $this->keysToLower($rawColumn);

        $dataType = $this->dataTypeMapper->createType($rawColumn['type'], $rawColumn['collation']);

        $column = $this->newColumn($rawColumn['field'], $dataType);
        $column->setNullable(strtolower($rawColumn['null']) == 'yes');
        $column->setAutoIncrement($rawColumn['extra'] == 'auto_increment');
        $column->setDefault($rawColumn['default']);
        $column->setOnUpdateCurrentTimestamp(strcasecmp($rawColumn['extra'], 'on update CURRENT_TIMESTAMP') == 0);
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

    protected function keysToLower(array $arr)
    {
        $keysToLower = [];

        foreach ($arr as $k => $v) {
            $keysToLower[strtolower($k)] = $v;
        }

        return $keysToLower;
    }
}
