<?php

namespace MilesAsylum\Schnoop\SchemaFactory\MySQL\Database;

use MilesAsylum\Schnoop\SchemaAdapter\MySQL\Database;
use PDO;

class DatabaseFactory implements DatabaseFactoryInterface
{
    /**
     * @var PDO
     */
    protected $pdo;

    /**
     * @var \PDOStatement
     */
    protected $sqlSelectSchemata;

    /**
     * DatabaseFactory constructor.
     * @param PDO $pdo
     */
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;

        $this->sqlSelectSchemata = $this->pdo->prepare(<<< SQL
SELECT
  schema_name,
  default_collation_name
FROM information_schema.SCHEMATA
WHERE SCHEMA_NAME = :databaseName
SQL
        );
    }

    /**
     * {@inheritdoc}
     */
    public function fetch($databaseName)
    {
        return $this->createFromRaw($this->fetchRaw($databaseName));
    }

    /**
     * {@inheritdoc}
     */
    public function fetchRaw($databaseName)
    {
        $this->sqlSelectSchemata->execute([':databaseName' => $databaseName]);

        return $this->sqlSelectSchemata->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * {@inheritdoc}
     */
    public function createFromRaw(array $rawDatabase)
    {
        $database = $this->newDatabase($rawDatabase['schema_name']);
        $database->setDefaultCollation($rawDatabase['default_collation_name']);

        return $database;
    }

    /**
     * {@inheritdoc}
     */
    public function newDatabase($databaseName)
    {
        return new Database($databaseName);
    }
}
