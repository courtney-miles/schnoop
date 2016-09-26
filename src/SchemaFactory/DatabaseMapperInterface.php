<?php

namespace MilesAsylum\Schnoop\SchemaFactory;

use MilesAsylum\Schnoop\Schema\DatabaseInterface;

interface DatabaseMapperInterface
{
    /**
     * @param $databaseName
     * @return DatabaseInterface
     */
    public function fetch($databaseName);
}
