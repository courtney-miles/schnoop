<?php

namespace MilesAsylum\Schnoop\Schema\MySQL\Index;

interface IndexedColumnInterface
{
    const COLLATION_ASC = 'asc';

    public function getColumn();

    public function getLength();

    public function hasLength();

    public function getCollation();
}
