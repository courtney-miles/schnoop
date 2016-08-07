<?php

namespace MilesAsylum\Schnoop\Schema\MySQL\DataType;

use MilesAsylum\Schnoop\Schema\MySQL\DataType\Option\NumericRangeInterface;
use MilesAsylum\Schnoop\Schema\MySQL\DataType\Option\SignedInterface;

interface NumericTypeInterface extends DataTypeInterface, NumericRangeInterface, SignedInterface
{
}
