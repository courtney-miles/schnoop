<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 19/07/16
 * Time: 7:12 AM
 */

namespace MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType;

use MilesAsylum\Schnoop\Schema\MySQL\DataType\SmallIntType;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType\AbstractIntTypeFactory;

class SmallintTypeFactory extends AbstractIntTypeFactory
{
    /**
     * @param $typeStr
     * @param null $collation
     * @return SmallIntType|bool
     */
    public static function create($typeStr, $collation = null)
    {
        if (!self::doRecognise($typeStr)) {
            return false;
        }

        return new SmallIntType(self::getDisplayWidth($typeStr), self::getSigned($typeStr));
    }

    /**
     * @param $typeStr
     * @return bool
     */
    public static function doRecognise($typeStr)
    {
        return self::matchIntPattern('/^smallint?/i', $typeStr);
    }
}