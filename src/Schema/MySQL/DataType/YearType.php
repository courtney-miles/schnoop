<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 11/07/16
 * Time: 4:33 PM
 */

namespace MilesAsylum\Schnoop\Schema\MySQL\DataType;

class YearType implements DataTypeInterface
{
    /**
     * @return string
     */
    public function getName()
    {
        return self::TYPE_YEAR;
    }

    /**
     * @return bool
     */
    public function doesAllowDefault()
    {
        return true;
    }

    /**
     * Cast a value from MySQL to a suitable PHP type.
     * @param mixed $value
     * @return mixed
     */
    public function cast($value)
    {
        return (int)$value;
    }
}