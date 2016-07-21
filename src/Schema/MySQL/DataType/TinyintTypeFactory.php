<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 18/07/16
 * Time: 4:43 PM
 */

namespace MilesAsylum\Schnoop\Schema\MySQL\DataType;


class TinyintTypeFactory extends AbstractIntTypeFactory
{
    /**
     * @param $typeStr
     * @param null $collation
     * @return TinyIntType|bool
     */
    public static function create($typeStr, $collation = null)
    {
        if (!self::doRecognise($typeStr)) {
            return false;
        }

        return new TinyIntType(self::getDisplayWidth($typeStr), self::getSigned($typeStr));
    }

    /**
     * @param $typeStr
     * @return bool
     */
    public static function doRecognise($typeStr)
    {
        return self::matchIntPattern('/^tinyint/i', $typeStr);
    }
}