<?php

namespace MilesAsylum\Schnoop\Tests\Schnoop\Schema\MySQL\Index;

use MilesAsylum\Schnoop\PHPUnit\Framework\SchnoopTestCase;
use MilesAsylum\Schnoop\Schema\MySQL\Index\IndexInterface;
use MilesAsylum\Schnoop\Schema\MySQL\Index\UniqueIndex;

class UniqueIndexTest extends SchnoopTestCase
{
    public function testConstructed()
    {
        $name = 'schnoop_idx';
        $indexedColumns = [];
        $indexType = IndexInterface::INDEX_TYPE_BTREE;
        $comment = 'Schnoop comment';

        $uniqueIndex = new UniqueIndex(
            $name,
            $indexedColumns,
            $indexType,
            $comment
        );

        $this->indexAsserts(
            $name, IndexInterface::INDEX_UNIQUE, $indexedColumns, $indexType, $comment, $uniqueIndex
        );
    }
}
