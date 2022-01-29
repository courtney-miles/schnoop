<?php

namespace MilesAsylum\Schnoop\SchemaFactory\MySQL\Constraint;

use MilesAsylum\SchnoopSchema\MySQL\Constraint\ForeignKey;
use MilesAsylum\SchnoopSchema\MySQL\Constraint\ForeignKeyColumn;
use PDO;

class ForeignKeyFactory implements ForeignKeyFactoryInterface
{
    /**
     * @var PDO
     */
    protected $pdo;

    /**
     * @var \PDOStatement
     */
    protected $stmtSelectForeignKeys;

    /**
     * ForeignKeyFactory constructor.
     */
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;

        $this->stmtSelectForeignKeys = $this->pdo->prepare(<<<SQL
SELECT
  table_name,
  constraint_name,
  column_name,
  ordinal_position,
  referenced_table_name,
  referenced_column_name
FROM information_schema.key_column_usage
WHERE referenced_table_name IS NOT NULL
      AND referenced_column_name IS NOT NULL
      AND table_schema = :database
      AND table_name = :table
SQL
        );
    }

    /**
     * {@inheritdoc}
     */
    public function fetch($tableName, $databaseName)
    {
        return $this->createFromRaw($this->fetchRaw($databaseName, $tableName));
    }

    /**
     * {@inheritdoc}
     */
    public function fetchRaw($databaseName, $tableName)
    {
        $this->stmtSelectForeignKeys->execute([':database' => $databaseName, ':table' => $tableName]);

        return $this->stmtSelectForeignKeys->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * {@inheritdoc}
     */
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

    /**
     * {@inheritdoc}
     */
    public function newForeignKey($keyName)
    {
        return new ForeignKey($keyName);
    }

    /**
     * {@inheritdoc}
     */
    public function newForeignKeyColumn($columnName, $referenceColumnName)
    {
        return new ForeignKeyColumn($columnName, $referenceColumnName);
    }
}
