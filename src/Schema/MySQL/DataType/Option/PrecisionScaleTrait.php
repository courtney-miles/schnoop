<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 19/06/16
 * Time: 9:17 AM
 */

namespace MilesAsylum\Schnoop\Schema\MySQL\DataType\Option;

trait PrecisionScaleTrait
{
    /**
     * @var int
     */
    protected $precision;

    /**
     * @var int
     */
    protected $scale;

    /**
     * @return int
     */
    public function getPrecision()
    {
        return $this->precision;
    }

    /**
     * @return int
     */
    public function getScale()
    {
        return $this->scale;
    }

    /**
     * @param int $precision
     * @param int $scale
     */
    public function setPrecisionScale($precision, $scale)
    {
        $this->precision = (int)$precision;
        $this->scale = (int)$scale;
    }
}