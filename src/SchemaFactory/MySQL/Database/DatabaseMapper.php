<?php

namespace MilesAsylum\Schnoop\SchemaFactory\MySQL\Database;

use MilesAsylum\Schnoop\SchemaFactory\DatabaseMapperInterface;
use MilesAsylum\Schnoop\Schema\Database;
use PDO;

class DatabaseMapper implements DatabaseMapperInterface
{
    /**
     * @var PDO
     */
    protected $pdo;

    /**
     * @var \PDOStatement
     */
    protected $sqlSelectSchemata;

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

    public function fetch($databaseName)
    {
        return $this->createFromRaw($this->fetchRaw($databaseName));
    }

    public function fetchRaw($databaseName)
    {
        $this->sqlSelectSchemata->execute([':databaseName' => $databaseName]);

        return $this->sqlSelectSchemata->fetch(PDO::FETCH_ASSOC);
    }

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
