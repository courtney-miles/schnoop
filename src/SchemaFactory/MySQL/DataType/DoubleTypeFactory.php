<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 19/07/16
 * Time: 8:13 PM
 */

namespace MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType;

use MilesAsylum\Schnoop\Schema\MySQL\DataType\DataTypeInterface;
use MilesAsylum\Schnoop\Schema\MySQL\DataType\DoubleType;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType\AbstractNumericPointTypeFactory;

class DoubleTypeFactory extends AbstractNumericPointTypeFactory
{
    /**
     * @param $typeStr
     * @param null $collation
     * @return DataTypeInterface|bool
     */
    public static function create($typeStr, $collation = null)
    {
        if (!self::doRecognise($typeStr)) {
            return false;
        }

        list ($precision, $scale) = self::getPrecisionScale($typeStr);

        return new DoubleType(self::getSigned($typeStr), $precision, $scale);
    }

    /**
     * @param $typeStr
     * @return bool
     */
    public static function doRecognise($typeStr)
    {
        return self::matchPointPattern('/^double/i', $typeStr);
    }
}