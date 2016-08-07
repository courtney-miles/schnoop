<?php

namespace MilesAsylum\Schnoop\Schema\MySQL\Index;

use MilesAsylum\Schnoop\Schema\MySQL\Column\ColumnInterface;

interface IndexedColumnInterface
{
    const COLLATION_ASC = 'asc';

    public function getColumnName();

    /**
     * @return ColumnInterface
     */
    public function getColumn();

    public function getLength();

    public function hasLength();

    public function getCollation();
}
