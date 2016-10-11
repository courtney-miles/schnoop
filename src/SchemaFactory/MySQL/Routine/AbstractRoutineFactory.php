<?php

namespace MilesAsylum\Schnoop\SchemaFactory\MySQL\Routine;

use MilesAsylum\Schnoop\SchemaFactory\MySQL\SetVar\SqlModeFactory;
use MilesAsylum\SchnoopSchema\MySQL\Routine\RoutineInterface;
use MilesAsylum\SchnoopSchema\MySQL\SetVar\SqlMode;

abstract class AbstractRoutineFactory
{
    /**
     * @var \PDO
     */
    protected $pdo;

    /**
     * @var \PDOStatement
     */
    protected $stmtSelectFunction;

    /**
     * @var ParametersFactory
     */
    protected $parametersFactory;

    /**
     * @var SqlModeFactory
     */
    protected $sqlModeFactory;

    /**
     * AbstractRoutineFactory constructor.
     * @param \PDO $pdo
     * @param ParametersFactory $parametersFactory
     * @param SqlModeFactory $sqlModeFactory
     */
    public function __construct(\PDO $pdo, ParametersFactory $parametersFactory, SqlModeFactory $sqlModeFactory)
    {
        $this->pdo = $pdo;
        $this->parametersFactory = $parametersFactory;
        $this->sqlModeFactory = $sqlModeFactory;

        $this->stmtSelectFunction = $this->pdo->prepare(<<<SQL
SELECT
  name,
  sql_data_access,
  is_deterministic,
  security_type,
  param_list,
  returns,
  body,
  definer,
  sql_mode,
  comment
FROM mysql.proc
WHERE db = :database
  AND type = :type
  AND name = :function
SQL
        );
    }

    /**
     * Populate the properties of the routine with the supplied row data.
     * @param RoutineInterface $routine
     * @param array $raw Row data that defines a routine.
     */
    protected function hydrateRoutine(RoutineInterface $routine, array $raw)
    {
        $routine->setDefiner($raw['definer']);
        $routine->setDataAccess(str_replace('_', ' ', $raw['sql_data_access']));
        $routine->setDeterministic(strtolower($raw['is_deterministic']) == 'yes');
        $routine->setSqlSecurity($raw['security_type']);
        $routine->setComment($raw['comment']);
        $routine->setSqlMode($this->sqlModeFactory->newSqlMode($raw['sql_mode']));

        $body = $raw['body'];
        $body = preg_replace('/^(BEGIN\s)/i', '', $body);
        $body = preg_replace('/(\sEND)$/i', '', $body);
        $routine->setBody($body);
    }
}
