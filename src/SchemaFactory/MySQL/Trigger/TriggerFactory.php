<?php

namespace MilesAsylum\Schnoop\SchemaFactory\MySQL\Trigger;

use MilesAsylum\Schnoop\SchemaFactory\MySQL\SetVar\SqlModeFactory;
use MilesAsylum\Schnoop\SchemaAdapter\MySQL\Trigger;
use PDO;

class TriggerFactory implements TriggerFactoryInterface
{
    protected $pdo;

    /**
     * @var SqlModeFactory
     */
    protected $sqlModeFactory;

    protected $qShowTriggerForTable;

    public function __construct(\PDO $pdo, SqlModeFactory $sqlModeFactory)
    {
        $this->pdo = $pdo;
        $this->sqlModeFactory = $sqlModeFactory;

        $this->qShowTriggerForTable = <<<SQL
SHOW TRIGGERS FROM `%s` WHERE `Table` = :tableName
SQL;
    }

    public function fetch($tableName, $databaseName)
    {
        return $this->createFromRaw($this->fetchRaw($tableName, $databaseName), $databaseName);
    }

    public function fetchRaw($tableName, $databaseName)
    {
        $stmt = $this->pdo->prepare(
            sprintf(
                $this->qShowTriggerForTable,
                $databaseName
            )
        );

        $stmt->execute([':tableName' => $tableName]);

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!empty($rows)) {
            foreach ($rows as $k => $row) {
                $rows[$k] = array_intersect_key(
                    $row,
                    array_fill_keys(
                        [
                            'Trigger',
                            'Event',
                            'Timing',
                            'Table',
                            'Statement',
                            'sql_mode',
                            'Definer'
                        ],
                        true
                    )
                );
            }
        }

        return $rows;
    }

    public function createFromRaw(array $rawTriggers, $databaseName)
    {
        $triggers = [];

        foreach ($rawTriggers as $rawTrigger) {
            $rawTrigger = $this->keysToLower($rawTrigger);

            $trigger = $this->newTrigger(
                $rawTrigger['trigger'],
                $rawTrigger['timing'],
                $rawTrigger['event'],
                $rawTrigger['table']
            );

            $trigger->setDefiner($rawTrigger['definer']);

            $statement = preg_replace(
                ['/^BEGIN\s/i', '/\sEND$/i'],
                ['', ''],
                $rawTrigger['statement']
            );
            $trigger->setBody($statement);

            $trigger->setDatabaseName($databaseName);
            $trigger->setSqlMode($this->sqlModeFactory->newSqlMode($rawTrigger['sql_mode']));

            $triggers[] = $trigger;
        }

        return $triggers;
    }

    public function newTrigger($name, $timing, $event, $tableName)
    {
        return new Trigger($name, $timing, $event, $tableName);
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
