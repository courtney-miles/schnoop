<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 11/07/16
 * Time: 4:26 PM
 */

namespace MilesAsylum\Schnoop\Schema\MySQL\DataType;

abstract class AbstractTimeType implements TimeTypeInterface
{
    protected $precision;

    public function __construct($precision = 0)
    {
        $this->precision = $precision;
    }

    public function getPrecision()
    {
        return $this->precision;
    }

    /**
     * @return bool
     */
    public function doesAllowDefault()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function cast($value)
    {
        return $value;
    }
}