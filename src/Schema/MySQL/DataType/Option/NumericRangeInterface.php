<?php

namespace MilesAsylum\Schnoop\Schema\MySQL\DataType\Option;

interface NumericRangeInterface
{
    public function getMinRange();
    
    public function getMaxRange();
}
