<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 20/07/16
 * Time: 7:23 AM
 */

namespace MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType;

use MilesAsylum\Schnoop\Schema\MySQL\DataType\MediumBlobType;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType\DataTypeFactoryInterface;

class MediumblobTypeFactory implements DataTypeFactoryInterface
{
    /**
     * @param $typeStr
     * @param null $collation
     * @return MediumBlobType|bool
     */
    public static function create($typeStr, $collation = null)
    {
        if (!self::doRecognise($typeStr)) {
            return false;
        }

        return new MediumBlobType();
    }

    /**
     * @param $typeStr
     * @return bool
     */
    public static function doRecognise($typeStr)
    {
        return preg_match('/^mediumblob$/i', $typeStr) === 1;
    }
}
