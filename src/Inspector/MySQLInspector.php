<?php

namespace MilesAsylum\Schnoop\Inspector;

class MySQLInspector implements InspectorInterface
{
    /**
     * @var \PDO
     */
    protected $pdo;

    /**
     * @var \PDOStatement
     */
    protected $stmtSelectDatabaseNames;

    /**
     * @var string
     */
    protected $querySelectTableNames;

    /**
     * @var \PDOStatement
     */
    protected $stmtSelectActiveDatabase;

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

        $this->stmtSelectActiveDatabase = $this->pdo->prepare(<<<SQL
SELECT DATABASE()
SQL
        );
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

    public function fetchActiveDatabase()
    {
        $this->stmtSelectActiveDatabase->execute();

        return $this->stmtSelectActiveDatabase->fetchColumn();
    }
}
