<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 20/07/16
 * Time: 6:19 PM
 */

namespace MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType;

use MilesAsylum\Schnoop\Schema\MySQL\DataType\BinaryType;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType\AbstractStringTypeFactory;

class BinaryTypeFactory extends AbstractStringTypeFactory
{
    /**
     * @param $typeStr
     * @param null $collation
     * @return BinaryType|bool
     */
    public static function create($typeStr, $collation = null)
    {
        if (!self::doRecognise($typeStr)) {
            return false;
        }

        return new BinaryType(self::getLength($typeStr));
    }

    /**
     * @param $typeStr
     * @return bool
     */
    public static function doRecognise($typeStr)
    {
        return preg_match('/^binary\(\d+\)/i', $typeStr) === 1;
    }
}
