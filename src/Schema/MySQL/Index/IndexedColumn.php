<?php

namespace MilesAsylum\Schnoop\Schema\MySQL\Index;

use MilesAsylum\Schnoop\Schema\MySQL\Column\ColumnInterface;

class IndexedColumn implements IndexedColumnInterface
{
    /**
     * @var ColumnInterface
     */
    protected $column;

    /**
     * @var int
     */
    protected $length;

    /**
     * @var string
     */
    protected $collation;

    /**
     * IndexedColumn constructor.
     * @param ColumnInterface $column
     * @param int|null $length
     * @param string $collation
     */
    public function __construct(ColumnInterface $column, $length, $collation)
    {
        $this->column = $column;
        $this->length = $length;
        $this->collation = $collation;
    }

    public function getColumn()
    {
        return $this->column;
    }

    public function getLength()
    {
        return $this->length;
    }

    public function hasLength()
    {
        return $this->length !== null;
    }

    public function getCollation()
    {
        return $this->collation;
    }
}