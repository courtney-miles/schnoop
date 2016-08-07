<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 19/07/16
 * Time: 8:23 PM
 */

namespace MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType;

use MilesAsylum\Schnoop\Schema\MySQL\DataType\DecimalType;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType\AbstractNumericPointTypeFactory;

class DecimalTypeFactory extends AbstractNumericPointTypeFactory
{
    /**
     * @param $typeStr
     * @param null $collation
     * @return DecimalType|bool
     */
    public static function create($typeStr, $collation = null)
    {
        if (!self::doRecognise($typeStr)) {
            return false;
        }

        list ($precision, $scale) = self::getPrecisionScale($typeStr);

        return new DecimalType(self::getSigned($typeStr), $precision, $scale);
    }

    /**
     * @param $typeStr
     * @return bool
     */
    public static function doRecognise($typeStr)
    {
        return self::matchPointPattern('/^decimal\(\d+,\d+\)/i', $typeStr);
    }
}
