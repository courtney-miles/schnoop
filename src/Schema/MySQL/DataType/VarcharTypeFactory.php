<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 20/07/16
 * Time: 7:30 PM
 */

namespace MilesAsylum\Schnoop\Schema\MySQL\DataType;

class VarcharTypeFactory extends AbstractStringTypeFactory
{
    /**
     * @param $typeStr
     * @param null $collation
     * @return VarCharType|bool
     */
    public static function create($typeStr, $collation = null)
    {
        if (!self::doRecognise($typeStr)) {
            return false;
        }

        return new VarCharType(self::getLength($typeStr), $collation);
    }

    /**
     * @param $typeStr
     * @return bool
     */
    public static function doRecognise($typeStr)
    {
        return preg_match('/^varchar\(\d+\)/i', $typeStr) === 1;
    }
}
