<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 20/07/16
 * Time: 7:10 PM
 */

namespace MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType;

use MilesAsylum\Schnoop\Schema\MySQL\DataType\BitType;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType\AbstractStringTypeFactory;

class BitTypeFactory extends AbstractStringTypeFactory
{
    /**
     * @param $typeStr
     * @param null $collation
     * @return BitType|bool
     */
    public static function create($typeStr, $collation = null)
    {
        if (!self::doRecognise($typeStr)) {
            return false;
        }

        return new BitType(self::getLength($typeStr));
    }

    /**
     * @param $typeStr
     * @return bool
     */
    public static function doRecognise($typeStr)
    {
        return preg_match('/^bit\(\d+\)/i', $typeStr) === 1;
    }
}
