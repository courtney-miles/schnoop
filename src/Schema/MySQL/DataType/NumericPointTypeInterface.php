<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 21/06/16
 * Time: 7:15 AM
 */

namespace MilesAsylum\Schnoop\Schema\MySQL\DataType;

use MilesAsylum\Schnoop\Schema\MySQL\DataType\Option\PrecisionScaleInterface;

interface NumericPointTypeInterface extends NumericTypeInterface, PrecisionScaleInterface
{

}