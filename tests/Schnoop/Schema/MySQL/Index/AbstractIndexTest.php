<?php

namespace MilesAsylum\Schnoop\Tests\Schnoop\Schema\MySQL\Index;

use MilesAsylum\Schnoop\PHPUnit\Framework\SchnoopTestCase;
use MilesAsylum\Schnoop\Schema\MySQL\Index\AbstractIndex;
use MilesAsylum\Schnoop\Schema\MySQL\Index\IndexInterface;

class AbstractIndexTest extends SchnoopTestCase
{
    public function testConstructed()
    {
        $name = 'schnoop_idx';
        $indexedColumns = [];
        $indexType = IndexInterface::INDEX_TYPE_BTREE;
        $comment = 'Schnoop comment';

        $abstractIndex = $this->getMockForAbstractClass(
            AbstractIndex::class,
            [
                $name,
                $indexedColumns,
                $indexType,
                $comment
            ]
        );

        $this->indexAsserts(
            $name, null, $indexedColumns, $indexType, $comment, $abstractIndex
        );
    }
}
