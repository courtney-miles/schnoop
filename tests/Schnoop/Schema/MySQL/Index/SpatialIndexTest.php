<?php

namespace MilesAsylum\Schnoop\Tests\Schnoop\Schema\MySQL\Index;

use MilesAsylum\Schnoop\PHPUnit\Framework\SchnoopTestCase;
use MilesAsylum\Schnoop\Schema\MySQL\Index\IndexInterface;
use MilesAsylum\Schnoop\Schema\MySQL\Index\SpatialIndex;

class SpatialIndexTest extends SchnoopTestCase
{
    public function testConstructed()
    {
        $name = 'schnoop_idx';
        $indexedColumns = [];
        $comment = 'Schnoop comment';

        $spatialIndex = new SpatialIndex(
            $name,
            $indexedColumns,
            $comment
        );

        $this->indexAsserts(
            $name, IndexInterface::INDEX_SPATIAL, $indexedColumns, IndexInterface::INDEX_TYPE_RTREE, $comment,
            $spatialIndex
        );
    }
}
