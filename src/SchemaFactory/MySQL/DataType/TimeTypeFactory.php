<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 21/07/16
 * Time: 4:21 PM
 */

namespace MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType;

use MilesAsylum\Schnoop\Schema\MySQL\DataType\TimeType;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType\AbstractTimeTypeFactory;

class TimeTypeFactory extends AbstractTimeTypeFactory
{
    /**
     * @param $typeStr
     * @param null $collation
     * @return TimeType|bool
     */
    public static function create($typeStr, $collation = null)
    {
        if (!self::doRecognise($typeStr)) {
            return false;
        }

        return new TimeType(self::getPrecision($typeStr));
    }

    /**
     * @param $typeStr
     * @return bool
     */
    public static function doRecognise($typeStr)
    {
        return preg_match('/^time(\(\d+\))?$/i', $typeStr) === 1;
    }
}
