<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 21/06/16
 * Time: 7:16 AM
 */

namespace MilesAsylum\Schnoop\Schema\MySQL\DataType;

use MilesAsylum\Schnoop\Schema\MySQL\DataType\Option\DisplayWidthInterface;

interface IntTypeInterface extends NumericTypeInterface, DisplayWidthInterface
{

}