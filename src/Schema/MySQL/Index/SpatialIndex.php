<?php

namespace MilesAsylum\Schnoop\Schema\MySQL\Index;

class SpatialIndex extends AbstractIndex
{
    public function __construct($name, array $indexedColumns, $comment)
    {
        parent::__construct($name, $indexedColumns, self::INDEX_TYPE_RTREE, $comment);
    }

    public function getType()
    {
        return self::INDEX_SPATIAL;
    }
}