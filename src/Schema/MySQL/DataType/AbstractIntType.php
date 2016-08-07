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
use MilesAsylum\Schnoop\Schema\MySQL\DataType\Option\QuoteNumericTrait;
use MilesAsylum\Schnoop\Schema\MySQL\DataType\Option\SignedTrait;

abstract class AbstractIntType implements IntTypeInterface
{
    use DisplayWidthTrait;
    use SignedTrait;
    use NumericRangeTrait;
    use QuoteNumericTrait;
    
    public function __construct($displayWidth, $isSigned, $minRange, $maxRange)
    {
        $this->setDisplayWidth($displayWidth);
        $this->setSigned($isSigned);
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

    public function __toString()
    {
        return implode(
            ' ',
            array_filter(
                [
                    strtoupper($this->getType()) . ($this->displayWidth > 0 ? '(' . $this->displayWidth .')' : null),
                    !$this->isSigned() ? 'UNSIGNED' : null
                ]
            )
        );
    }
}
