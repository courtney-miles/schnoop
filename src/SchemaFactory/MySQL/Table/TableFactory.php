<?php

namespace MilesAsylum\Schnoop\SchemaFactory\MySQL\Table;

use MilesAsylum\Schnoop\SchemaAdapter\MySQL\Table;
use PDO;

class TableFactory implements TableFactoryInterface
{
    /**
     * @var PDO
     */
    protected $pdo;

    /**
     * @var
     */
    protected $sqlShowTableStatus;

    /**
     * TableFactory constructor.
     */
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;

        $this->sqlShowTableStatus = <<<SQL
SHOW TABLE STATUS FROM `%s` WHERE `Name` = :table AND `Engine` IS NOT NULL 
SQL;
    }

    /**
     * {@inheritdoc}
     */
    public function fetch($tableName, $databaseName)
    {
        $rawTable = $this->fetchRaw($tableName, $databaseName);

        return null !== $rawTable ? $this->createFromRaw($rawTable, $databaseName) : null;
    }

    /**
     * {@inheritdoc}
     */
    public function fetchRaw($tableName, $databaseName)
    {
        $raw = null;

        $stmt = $this->pdo->prepare(
            sprintf(
                $this->sqlShowTableStatus,
                $databaseName
            )
        );
        $stmt->execute([':table' => $tableName]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!empty($row)) {
            $raw = array_intersect_key(
                $row,
                array_fill_keys(
                    [
                        'Name',
                        'Engine',
                        'Row_format',
                        'Collation',
                        'Comment',
                    ],
                    true
                )
            );
        }

        return $raw;
    }

    /**
     * {@inheritdoc}
     */
    public function createFromRaw(array $rawTable, $databaseName)
    {
        $rawTable = $this->keysToLower($rawTable);

        $table = $this->newTable($rawTable['name'], $databaseName);
        $table->setEngine($rawTable['engine']);
        $table->setRowFormat($rawTable['row_format']);
        $table->setDefaultCollation($rawTable['collation']);
        $table->setComment($rawTable['comment']);

        return $table;
    }

    /**
     * {@inheritdoc}
     */
    public function newTable($tableName, $databaseName)
    {
        return new Table($databaseName, $tableName);
    }

    /**
     * Convert the associate keys of an array to lower-case.
     *
     * @return array copy the supplied array with lower-case keys
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
