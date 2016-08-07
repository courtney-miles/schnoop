<?php

namespace MilesAsylum\Schnoop\Tests\Schnoop\Schema\MySQL\Index;

use MilesAsylum\Schnoop\PHPUnit\Framework\SchnoopTestCase;
use MilesAsylum\Schnoop\Schema\MySQL\Index\Index;
use MilesAsylum\Schnoop\Schema\MySQL\Index\IndexInterface;

class IndexTest extends SchnoopTestCase
{
    public function testConstructed()
    {
        $name = 'schnoop_idx';
        $indexedColumns = [];
        $indexType = IndexInterface::INDEX_TYPE_BTREE;
        $comment = 'Schnoop comment';

        $index = new Index(
            $name,
            $indexedColumns,
            $indexType,
            $comment
        );

        $this->indexAsserts(
            $name,
            IndexInterface::INDEX_INDEX,
            $indexedColumns,
            $indexType,
            $comment,
            $index
        );
    }
}
