<?php

namespace MilesAsylum\Schnoop\Schema;

interface DataTypeInterface
{
    /**
     * @return string
     */
    public function getType();

    public function __toString();
}
