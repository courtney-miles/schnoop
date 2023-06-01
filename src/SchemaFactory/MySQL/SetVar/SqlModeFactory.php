<?php

namespace MilesAsylum\Schnoop\SchemaFactory\MySQL\SetVar;

use MilesAsylum\SchnoopSchema\MySQL\SetVar\SqlMode;

class SqlModeFactory
{
    /**
     * Create an SQL Mode object.
     *
     * @return SqlMode
     */
    public function newSqlMode($mode)
    {
        return new SqlMode($mode);
    }
}
