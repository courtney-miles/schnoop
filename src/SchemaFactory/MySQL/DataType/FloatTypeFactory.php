<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 19/07/16
 * Time: 5:26 PM
 */

namespace MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType;

use MilesAsylum\Schnoop\Schema\MySQL\DataType\FloatType;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType\AbstractNumericPointTypeFactory;

class FloatTypeFactory extends AbstractNumericPointTypeFactory
{
    /**
     * @param $typeStr
     * @param null $collation
     * @return FloatType|bool
     */
    public static function create($typeStr, $collation = null)
    {
        if (!self::doRecognise($typeStr)) {
            return false;
        }

        list ($precision, $scale) = self::getPrecisionScale($typeStr);

        return new FloatType(self::getSigned($typeStr), $precision, $scale);
    }

    /**
     * @param $typeStr
     * @return bool
     */
    public static function doRecognise($typeStr)
    {
        return self::matchPointPattern('/^float/i', $typeStr);
    }
}
