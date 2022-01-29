<?php

namespace MilesAsylum\Schnoop\SchemaFactory\MySQL\Routine;

use MilesAsylum\Schnoop\SchemaAdapter\MySQL\RoutineProcedure;

class ProcedureFactory extends AbstractRoutineFactory implements ProcedureFactoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function fetch($procedureName, $databaseName)
    {
        return $this->createFromRaw(
            $this->fetchRaw($procedureName, $databaseName)
        );
    }

    /**
     * {@inheritdoc}
     */
    public function fetchRaw($functionName, $databaseName)
    {
        $this->stmtSelectFunction->execute(
            [
                ':database' => $databaseName,
                ':type' => 'PROCEDURE',
                ':function' => $functionName,
            ]
        );

        return $this->stmtSelectFunction->fetch(\PDO::FETCH_ASSOC);
    }

    /**
     * {@inheritdoc}
     */
    public function createFromRaw(array $raw)
    {
        $procedure = $this->newProcedure($raw['name']);
        $this->hydrateRoutine($procedure, $raw);
        $procedure->setParameters($this->parametersFactory->createParameters($raw['param_list']));

        return $procedure;
    }

    /**
     * {@inheritdoc}
     */
    public function newProcedure($name)
    {
        return new RoutineProcedure($name);
    }
}
