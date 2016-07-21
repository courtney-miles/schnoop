<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 14/07/16
 * Time: 7:22 AM
 */

namespace MilesAsylum\Schnoop\Schema\MySQL\DataType;

class IntTypeFactory extends AbstractIntTypeFactory
{
    public static function create($typeStr, $collation = null)
    {
        if (!self::doRecognise($typeStr)) {
            return false;
        }

        return new IntType(self::getDisplayWidth($typeStr), self::getSigned($typeStr));
    }

    public static function doRecognise($typeStr)
    {
        return self::matchIntPattern('/^int(eger)?/i', $typeStr);
    }
}