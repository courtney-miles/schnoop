<?php

namespace MilesAsylum\Schnoop\Inspector;

class MySQLInspector implements InspectorInterface
{
    /**
     * @var \PDO
     */
    protected $pdo;

    protected $stmtSelectDatabaseNames;

    protected $querySelectTableNames;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;

        $this->stmtSelectDatabaseNames = $this->pdo->prepare(<<<SQL
SHOW DATABASES;
SQL
        );

        $this->querySelectTableNames = <<<SQL
SHOW TABLES FROM `%s`;
SQL;
    }

    public function fetchDatabaseList()
    {
        $this->stmtSelectDatabaseNames->execute();

        return $this->stmtSelectDatabaseNames->fetchAll(\PDO::FETCH_COLUMN);
    }

    public function fetchTableList($database)
    {
        $stmt = $this->pdo->query(
            sprintf(
                $this->querySelectTableNames,
                $database
            )
        );

        return $stmt->fetchAll(\PDO::FETCH_COLUMN);
    }
}
