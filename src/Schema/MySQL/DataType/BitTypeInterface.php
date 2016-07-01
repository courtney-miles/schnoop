<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 28/06/16
 * Time: 4:59 PM
 */

namespace MilesAsylum\Schnoop\Schema\MySQL\DataType;

use MilesAsylum\Schnoop\Schema\MySQL\DataType\Option\LengthInterface;
use MilesAsylum\Schnoop\Schema\MySQL\DataType\Option\NumericRangeInterface;

interface BitTypeInterface extends DataTypeInterface, LengthInterface, NumericRangeInterface
{

}