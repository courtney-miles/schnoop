<?php

namespace MilesAsylum\Schnoop\Tests\Schnoop\Schema\MySQL\Index;

use MilesAsylum\Schnoop\Schema\MySQL\Column\ColumnInterface;
use MilesAsylum\Schnoop\Schema\MySQL\Index\IndexedColumn;
use MilesAsylum\Schnoop\Schema\MySQL\Index\IndexedColumnInterface;

class IndexedColumnTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider constructProvider
     * @param ColumnInterface $column
     * @param int|null $length
     * @param string $collation
     */
    public function testConstructed(ColumnInterface $column, $length, $collation, $expectHasLength)
    {
        $indexedColumn = new IndexedColumn($column, $length, $collation);

        $this->assertSame($column, $indexedColumn->getColumn());
        $this->assertSame($expectHasLength, $indexedColumn->hasLength());
        $this->assertSame($length, $indexedColumn->getLength());
        $this->assertSame($collation, $indexedColumn->getCollation());
    }

    /**
     * @see testConstructed
     * @return array
     */
    public function constructProvider()
    {
        $mockColumn = $this->createMock(ColumnInterface::class);

        return [
            [
                $mockColumn,
                123,
                IndexedColumnInterface::COLLATION_ASC,
                true
            ]
        ];
    }
}
