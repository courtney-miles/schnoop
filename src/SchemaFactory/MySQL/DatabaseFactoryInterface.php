<?php

namespace MilesAsylum\Schnoop\SchemaFactory\MySQL;

use MilesAsylum\Schnoop\SchemaAdapter\MySQL\DatabaseInterface;

interface DatabaseFactoryInterface
{
    /**
     * Fetch a database from the server.
     *
     * @param string $databaseName
     *
     * @return DatabaseInterface
     */
    public function fetch($databaseName);
}
