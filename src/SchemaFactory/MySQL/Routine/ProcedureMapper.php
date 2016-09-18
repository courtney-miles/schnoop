<?php

namespace MilesAsylum\Schnoop\SchemaFactory\MySQL\Routine;

use MilesAsylum\SchnoopSchema\MySQL\Routine\ProcedureRoutine;

class ProcedureMapper extends AbstractRoutineMapper
{
    /**
     * @param $databaseName
     * @param $functionName
     * @return ProcedureRoutine
     */
    public function fetch($databaseName, $functionName)
    {
        return $this->createFromRaw(
            $this->fetchRaw($databaseName, $functionName)
        );
    }

    public function fetchRaw($databaseName, $functionName)
    {
        $this->stmtSelectFunction->execute(
            [
                ':database' => $databaseName,
                ':type' => 'PROCEDURE',
                ':function' => $functionName
            ]
        );

        return $this->stmtSelectFunction->fetch(\PDO::FETCH_ASSOC);
    }

    /**
     * @param array $raw
     * @return ProcedureRoutine
     */
    public function createFromRaw(array $raw)
    {
        $procedure = $this->newProcedure($raw['name']);
        $this->hydrateRoutine($procedure, $raw);
        $procedure->setParameters($this->parametersFactory->createParameters($raw['param_list']));

        return $procedure;
    }

    /**
     * @param $name
     * @return ProcedureRoutine
     */
    public function newProcedure($name)
    {
        return new ProcedureRoutine($name);
    }
}
