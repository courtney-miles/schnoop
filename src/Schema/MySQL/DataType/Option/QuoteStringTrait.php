<?php

namespace MilesAsylum\Schnoop\Schema\MySQL\DataType\Option;

trait QuoteStringTrait
{
    public function quote($value)
    {
        return "'" . addslashes($value) . "'";
    }
}
