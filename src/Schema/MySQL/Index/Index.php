<?php

namespace MilesAsylum\Schnoop\Schema\MySQL\Index;

class Index extends AbstractIndex
{
    public function getType()
    {
        return self::INDEX_INDEX;
    }
}
