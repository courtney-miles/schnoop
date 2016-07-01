<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 20/06/16
 * Time: 2:04 PM
 */

namespace MilesAsylum\Schnoop\Schema\MySQL\DataType;

use MilesAsylum\Schnoop\Schema\MySQL\DataType\Option\NumericRangeInterface;
use MilesAsylum\Schnoop\Schema\MySQL\DataType\Option\SignedInterface;

interface NumericTypeInterface extends DataTypeInterface, NumericRangeInterface, SignedInterface
{

}