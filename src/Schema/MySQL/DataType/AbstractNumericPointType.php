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
     * @param bool $signed
     * @param int $precision
     * @param int $scale
     */
    public function __construct($signed, $precision = null, $scale = null)
    {
        $this->setSigned($signed);

        if (isset($precision, $scale)) {
            $this->setPrecisionScale($precision, $scale);

            $maxRange = str_repeat('9', $precision - $scale) . '.' . str_repeat('9', $scale);
            $minRange = $signed ? '-' . $maxRange : '0';
            $this->setRange($minRange, $maxRange);
        }
    }
    
    public function doesAllowDefault()
    {
        return true;
    }
}
