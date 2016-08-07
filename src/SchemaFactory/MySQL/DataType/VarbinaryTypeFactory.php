<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 20/07/16
 * Time: 7:03 PM
 */

namespace MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType;

use MilesAsylum\Schnoop\Schema\MySQL\DataType\VarBinaryType;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType\AbstractStringTypeFactory;

class VarbinaryTypeFactory extends AbstractStringTypeFactory
{
    /**
     * @param $typeStr
     * @param null $collation
     * @return VarBinaryType|bool
     */
    public static function create($typeStr, $collation = null)
    {
        if (!self::doRecognise($typeStr)) {
            return false;
        }

        return new VarBinaryType(self::getLength($typeStr));
    }

    /**
     * @param $typeStr
     * @return bool
     */
    public static function doRecognise($typeStr)
    {
        return preg_match('/^varbinary\(\d+\)/i', $typeStr) === 1;
    }
}
