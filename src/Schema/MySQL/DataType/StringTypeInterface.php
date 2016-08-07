<?php

namespace MilesAsylum\Schnoop\Schema\MySQL\DataType;

use MilesAsylum\Schnoop\Schema\MySQL\DataType\Option\LengthInterface;
use MilesAsylum\Schnoop\Schema\MySQL\DataType\Option\CollationInterface;

interface StringTypeInterface extends DataTypeInterface, CollationInterface, LengthInterface
{
}
