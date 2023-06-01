<?php

namespace MilesAsylum\Schnoop\SchemaFactory\MySQL\Routine;

use MilesAsylum\Schnoop\SchemaFactory\MySQL\SetVar\SqlModeFactory;
use MilesAsylum\SchnoopSchema\MySQL\Routine\RoutineInterface;

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
     */
    public function __construct(\PDO $pdo, ParametersFactory $parametersFactory, SqlModeFactory $sqlModeFactory)
    {
        $this->pdo = $pdo;
        $this->parametersFactory = $parametersFactory;
        $this->sqlModeFactory = $sqlModeFactory;

        $this->stmtSelectFunction = $this->pdo->prepare(<<<TXT
SELECT
  ROUTINE_NAME AS name,
  REPLACE(SQL_DATA_ACCESS, ' ', '_') AS sql_data_access,
  IS_DETERMINISTIC AS is_deterministic,
  SECURITY_TYPE AS security_type,
  (
    SELECT GROUP_CONCAT(CONCAT(PARAMETER_NAME, ' ', UPPER(DTD_IDENTIFIER)) SEPARATOR ', ')
    FROM information_schema.parameters p
    WHERE p.SPECIFIC_NAME = r.ROUTINE_NAME
        AND p.SPECIFIC_SCHEMA = r.ROUTINE_SCHEMA
        AND ORDINAL_POSITION > 0
    ) AS param_list,
  CONCAT(
      IFNULL(DTD_IDENTIFIER, ''),
      IF (CHARACTER_SET_NAME IS NOT NULL, CONCAT(' CHARSET ', CHARACTER_SET_NAME), '')
  ) AS returns,
  ROUTINE_DEFINITION AS body,
  DEFINER AS definer,
  SQL_MODE AS sql_mode,
  ROUTINE_COMMENT AS comment
FROM information_schema.ROUTINES r
WHERE ROUTINE_SCHEMA = :database
  AND ROUTINE_TYPE = :type
  AND ROUTINE_NAME = :function
TXT
        );
    }

    /**
     * Populate the properties of the routine with the supplied row data.
     *
     * @param array $raw row data that defines a routine
     */
    protected function hydrateRoutine(RoutineInterface $routine, array $raw)
    {
        $routine->setDefiner($raw['definer']);
        $routine->setDataAccess(str_replace('_', ' ', $raw['sql_data_access']));
        $routine->setDeterministic('yes' == strtolower($raw['is_deterministic']));
        $routine->setSqlSecurity($raw['security_type']);
        $routine->setComment($raw['comment']);
        $routine->setSqlMode($this->sqlModeFactory->newSqlMode($raw['sql_mode']));

        $body = $raw['body'];
        $body = preg_replace('/^(BEGIN\s)/i', '', $body);
        $body = preg_replace('/(\sEND)$/i', '', $body);
        $routine->setBody($body);
    }
}
