<?php

namespace MilesAsylum\Schnoop\Tests\Schnoop\Schema\MySQL\Index;

use MilesAsylum\Schnoop\PHPUnit\Framework\SchnoopTestCase;
use MilesAsylum\Schnoop\Schema\MySQL\Index\FullTextIndex;
use MilesAsylum\Schnoop\Schema\MySQL\Index\IndexInterface;

class FullTextIndexTest extends SchnoopTestCase
{
    public function testConstruct()
    {
        $name = 'schnoop_idx';
        $indexedColumns = [];
        $comment = 'Schnoop comment';

        $fullTextIndex = new FullTextIndex(
            $name,
            $indexedColumns,
            $comment
        );

        $this->indexAsserts(
            $name, IndexInterface::INDEX_FULLTEXT, $indexedColumns, IndexInterface::INDEX_TYPE_FULLTEXT, $comment,
            $fullTextIndex
        );
    }
}
