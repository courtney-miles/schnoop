<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 21/07/16
 * Time: 4:27 PM
 */

namespace MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType;

use MilesAsylum\Schnoop\Schema\MySQL\DataType\TimestampType;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType\AbstractTimeTypeFactory;

class TimestampTypeFactory extends AbstractTimeTypeFactory
{
    /**
     * @param $typeStr
     * @param null $collation
     * @return TimestampType|bool
     */
    public static function create($typeStr, $collation = null)
    {
        if (!self::doRecognise($typeStr)) {
            return false;
        }

        return new TimestampType(self::getPrecision($typeStr));
    }

    /**
     * @param $typeStr
     * @return bool
     */
    public static function doRecognise($typeStr)
    {
        return preg_match('/^timestamp(\(\d+\))?$/i', $typeStr) === 1;
    }
}
