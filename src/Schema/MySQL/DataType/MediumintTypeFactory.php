<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 19/07/16
 * Time: 7:21 AM
 */

namespace MilesAsylum\Schnoop\Schema\MySQL\DataType;

class MediumintTypeFactory extends AbstractIntTypeFactory
{

    /**
     * @param $typeStr
     * @param null $collation
     * @return MediumIntType|bool
     */
    public static function create($typeStr, $collation = null)
    {
        if (!self::doRecognise($typeStr)) {
            return false;
        }

        return new MediumIntType(self::getDisplayWidth($typeStr), self::getSigned($typeStr));
    }

    /**
     * @param $typeStr
     * @return bool
     */
    public static function doRecognise($typeStr)
    {
        return self::matchIntPattern('/^mediumint?/i', $typeStr);
    }
}