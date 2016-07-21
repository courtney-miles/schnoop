<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 19/07/16
 * Time: 7:25 AM
 */

namespace MilesAsylum\Schnoop\Schema\MySQL\DataType;

class BigintTypeFactory extends AbstractIntTypeFactory
{
    /**
     * @param $typeStr
     * @param null $collation
     * @return BigIntType|bool
     */
    public static function create($typeStr, $collation = null)
    {
        if (!self::doRecognise($typeStr)) {
            return false;
        }

        return new BigIntType(self::getDisplayWidth($typeStr), self::getSigned($typeStr));
    }

    /**
     * @param $typeStr
     * @return bool
     */
    public static function doRecognise($typeStr)
    {
        return self::matchIntPattern('/^bigint?/i', $typeStr);
    }
}