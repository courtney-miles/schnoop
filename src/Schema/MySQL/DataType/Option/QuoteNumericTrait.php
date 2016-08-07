<?php

namespace MilesAsylum\Schnoop\Schema\MySQL\DataType\Option;

trait QuoteNumericTrait
{
    public function quote($value)
    {
        return $value;
    }
}
