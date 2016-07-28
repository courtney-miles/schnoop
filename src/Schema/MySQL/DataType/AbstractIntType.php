<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 18/06/16
 * Time: 6:21 PM
 */

namespace MilesAsylum\Schnoop\Schema\MySQL\DataType;

use MilesAsylum\Schnoop\Schema\MySQL\DataType\Option\DisplayWidthTrait;
use MilesAsylum\Schnoop\Schema\MySQL\DataType\Option\NumericRangeTrait;
use MilesAsylum\Schnoop\Schema\MySQL\DataType\Option\SignedTrait;

abstract class AbstractIntType implements IntTypeInterface
{
    use DisplayWidthTrait;
    use SignedTrait;
    use NumericRangeTrait;
    
    public function __construct($displayWidth, $signed, $minRange, $maxRange)
    {
        $this->setDisplayWidth($displayWidth);
        $this->setSigned($signed);
        $this->setRange($minRange, $maxRange);
    }
    
    public function doesAllowDefault()
    {
        return true;
    }

    public function cast($value)
    {
        return (int)$value;
    }
}
