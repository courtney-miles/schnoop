<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 19/06/16
 * Time: 10:06 AM
 */

namespace MilesAsylum\Schnoop\Schema\MySQL\DataType;

use MilesAsylum\Schnoop\Schema\MySQL\DataType\Option\NumericRangeTrait;
use MilesAsylum\Schnoop\Schema\MySQL\DataType\Option\PrecisionScaleTrait;
use MilesAsylum\Schnoop\Schema\MySQL\DataType\Option\SignedTrait;

abstract class AbstractNumericPointType implements NumericPointTypeInterface
{
    use PrecisionScaleTrait;
    use SignedTrait;
    use NumericRangeTrait;

    /**
     * AbstractNumericPointType constructor.
     * @param int $precision
     * @param int $scale
     * @param bool $signed
     */
    public function __construct($precision, $scale, $signed)
    {
        $maxRange = str_repeat('9', $precision - $scale) . '.' . str_repeat('9', $scale);
        $minRange = $signed ? '-' . $maxRange : '0';
        
        $this->setPrecisionScale($precision, $scale);
        $this->setSigned($signed);
        $this->setRange($minRange, $maxRange);
    }
    
    public function allowDefault()
    {
        return true;
    }
}