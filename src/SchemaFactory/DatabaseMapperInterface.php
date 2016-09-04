<?php

namespace MilesAsylum\Schnoop\SchemaFactory;

use MilesAsylum\SchnoopSchema\MySQL\Database\DatabaseInterface;

interface DatabaseMapperInterface
{
    /**
     * @param $databaseName
     * @return DatabaseInterface
     */
    public function fetch($databaseName);
}
