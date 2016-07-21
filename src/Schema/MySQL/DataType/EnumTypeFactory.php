<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 21/07/16
 * Time: 7:19 AM
 */

namespace MilesAsylum\Schnoop\Schema\MySQL\DataType;

class EnumTypeFactory extends AbstractOptionsTypeFactory
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

        return new EnumType(self::getOptions($typeStr), $collation);
    }

    /**
     * @param $typeStr
     * @return bool
     */
    public static function doRecognise($typeStr)
    {
        return preg_match('/^enum\(.+\)$/i', $typeStr) === 1;
    }
}
