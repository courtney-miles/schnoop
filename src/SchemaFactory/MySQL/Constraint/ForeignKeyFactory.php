<?php

namespace MilesAsylum\Schnoop\SchemaFactory\MySQL\Constraint;

use MilesAsylum\SchnoopSchema\MySQL\Constraint\ForeignKey;
use MilesAsylum\SchnoopSchema\MySQL\Constraint\ForeignKeyColumn;
use PDO;

class ForeignKeyFactory implements ForeignKeyFactoryInterface
{
    /**
     * @var \PDO
     */
    protected $pdo;

    /**
     * @var \PDOStatement
     */
    protected $stmtSelectForeignKeys;

    /**
     * ForeignKeyFactory constructor.
     */
    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;

        $this->stmtSelectForeignKeys = $this->pdo->prepare(<<<SQL
SELECT
  TABLE_NAME,
  CONSTRAINT_NAME,
  COLUMN_NAME,
  ORDINAL_POSITION,
  REFERENCED_TABLE_NAME,
  REFERENCED_COLUMN_NAME
FROM information_schema.key_column_usage
WHERE REFERENCED_TABLE_NAME IS NOT NULL
      AND REFERENCED_COLUMN_NAME IS NOT NULL
      AND TABLE_SCHEMA = :database
      AND TABLE_NAME = :table
SQL
        );
    }

    public function fetch($tableName, $databaseName)
    {
        return $this->createFromRaw($this->fetchRaw($databaseName, $tableName));
    }

    public function fetchRaw($databaseName, $tableName)
    {
        $this->stmtSelectForeignKeys->execute([':database' => $databaseName, ':table' => $tableName]);

        $rows = $this->stmtSelectForeignKeys->fetchAll(\PDO::FETCH_ASSOC);

        // Emulate PDO::setAttribute(PDO::ATTR_STRINGIFY_FETCHES, true).
        // We don't want to screw with connection attributes.
        foreach ($rows as $k => $row) {
            $row = array_map(
                static function ($v) {
                    return null !== $v ? (string) $v : $v;
                },
                $row
            );
            $rows[$k] = $row;
        }

        return $rows;
    }

    public function createFromRaw(array $rawTableFKs)
    {
        /** @var ForeignKey[] $foreignKeys */
        $foreignKeys = [];

        /** @var ForeignKeyColumn[][] $foreignKeyForeignKeyColumns */
        $foreignKeyForeignKeyColumns = [];

        foreach ($rawTableFKs as $rawTableFK) {
            $keyName = $rawTableFK['constraint_name'];

            if (!isset($foreignKeys[$keyName])) {
                $foreignKey = $this->newForeignKey($keyName);
                $foreignKey->setTableName($rawTableFK['table_name']);
                $foreignKey->setReferenceTableName($rawTableFK['referenced_table_name']);
                $foreignKeys[$keyName] = $foreignKey;
            }

            $foreignKeyForeignKeyColumns[$keyName][$rawTableFK['ordinal_position']] = $this->newForeignKeyColumn(
                $rawTableFK['column_name'],
                $rawTableFK['referenced_column_name']
            );
        }

        foreach ($foreignKeys as $keyName => $foreignKey) {
            ksort($foreignKeyForeignKeyColumns[$keyName]);
            $foreignKey->setForeignKeyColumns($foreignKeyForeignKeyColumns[$keyName]);
        }

        return $foreignKeys;
    }

    public function newForeignKey($keyName)
    {
        return new ForeignKey($keyName);
    }

    public function newForeignKeyColumn($columnName, $referenceColumnName)
    {
        return new ForeignKeyColumn($columnName, $referenceColumnName);
    }
}
