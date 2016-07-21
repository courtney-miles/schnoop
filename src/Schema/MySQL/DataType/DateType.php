<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 11/07/16
 * Time: 4:14 PM
 */

namespace MilesAsylum\Schnoop\Schema\MySQL\DataType;

class DateType implements DataTypeInterface
{
    /**
     * @return string
     */
    public function getName()
    {
        return self::TYPE_DATE;
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
