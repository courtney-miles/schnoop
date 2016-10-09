<?php

namespace MilesAsylum\Schnoop\SchemaFactory\MySQL;

use MilesAsylum\Schnoop\SchemaAdapter\MySQL\DatabaseInterface;

interface DatabaseFactoryInterface
{
    /**
     * @param $databaseName
     * @return DatabaseInterface
     */
    public function fetch($databaseName);
}
