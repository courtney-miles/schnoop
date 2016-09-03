<?php

namespace MilesAsylum\Schnoop\SchemaFactory\MySQL\Database;

use MilesAsylum\Schnoop\SchemaFactory\DatabaseMapperInterface;
use MilesAsylum\SchnoopSchema\MySQL\Database\Database;
use PDO;

class DatabaseMapper implements DatabaseMapperInterface
{
    /**
     * @var PDO
     */
    protected $pdo;

    protected $sqlSelectDatabaseAttributes;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;

        $this->sqlSelectDatabaseAttributes = <<< SQL
SELECT
  DATABASE()               AS `name`,
  @@character_set_database AS `character_set_database`,
  @@collation_database     AS `collation_database`
SQL;
    }

    public function fetch($databaseName)
    {
        return $this->createFromRaw($this->fetchRaw($databaseName));
    }

    public function fetchRaw($databaseName)
    {
        $activeDatabaseName = $this->fetchActiveDatabaseName();

        if ($activeDatabaseName != $databaseName) {
            $this->setActiveDatabase($databaseName);
        }

        $row = $this->pdo
            ->query($this->sqlSelectDatabaseAttributes)
            ->fetch(PDO::FETCH_ASSOC);

        if ($activeDatabaseName != $databaseName) {
            $this->setActiveDatabase($activeDatabaseName);
        }

        return $row;
    }

    public function createFromRaw(array $rawDatabase)
    {
        return new Database(
            $rawDatabase['name'],
            $rawDatabase['collation_database']
        );
    }

    protected function setActiveDatabase($databaseName)
    {
        $this->pdo->query("USE `$databaseName`");
    }

    protected function fetchActiveDatabaseName()
    {
        return $this->pdo->query('SELECT DATABASE()')->fetchColumn();
    }
}
