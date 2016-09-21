<?php

namespace MilesAsylum\Schnoop\SchemaFactory\MySQL\SetVar;

use MilesAsylum\SchnoopSchema\MySQL\SetVar\SqlMode;

class SqlModeFactory
{
    public function newSqlMode($mode)
    {
        return new SqlMode($mode);
    }
}
