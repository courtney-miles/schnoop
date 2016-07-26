<?php

namespace MilesAsylum\Schnoop\Schema\MySQL\Index;

abstract class AbstractIndex implements IndexInterface
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var IndexedColumnInterface[]
     */
    protected $indexedColumns;

    /**
     * @var string
     */
    protected $indexType;

    /**
     * @var string
     */
    protected $comment;

    /**
     * Index constructor.
     * @param string $name
     * @param IndexedColumnInterface[] $indexedColumns
     * @param string $indexType
     * @param string $comment
     */
    public function __construct($name, $indexedColumns, $indexType, $comment)
    {
        $this->name = $name;
        $this->indexedColumns = $indexedColumns;
        $this->indexType = $indexType;
        $this->comment = $comment;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getIndexType()
    {
        return $this->indexType;
    }

    /**
     * @return IndexedColumnInterface[]
     */
    public function getIndexedColumns()
    {
        return $this->indexedColumns;
    }

    public function getComment()
    {
        return $this->comment;
    }
}
