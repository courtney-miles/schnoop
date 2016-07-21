<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 20/07/16
 * Time: 9:51 PM
 */

namespace MilesAsylum\Schnoop\Schema\MySQL\DataType;

class SetTypeFactory extends AbstractOptionsTypeFactory
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

        return new SetType(self::getOptions($typeStr), $collation);
    }

    /**
     * @param $typeStr
     * @return bool
     */
    public static function doRecognise($typeStr)
    {
        return preg_match('/^set\(.+\)$/i', $typeStr) === 1;
    }
}
