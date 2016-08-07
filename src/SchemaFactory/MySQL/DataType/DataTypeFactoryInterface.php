<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 14/07/16
 * Time: 7:19 AM
 */

namespace MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType;

use MilesAsylum\Schnoop\Schema\MySQL\DataType\DataTypeInterface;

interface DataTypeFactoryInterface
{
    /**
     * @param $typeStr
     * @param null $collation
     * @return DataTypeInterface|bool
     */
    public static function create($typeStr, $collation = null);

    /**
     * @param $typeStr
     * @return bool
     */
    public static function doRecognise($typeStr);
}
