<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 20/07/16
 * Time: 6:25 PM
 */

namespace MilesAsylum\Schnoop\Schema\MySQL\DataType;

abstract class AbstractStringTypeFactory implements DataTypeFactoryInterface
{
    protected static function getLength($typeStr)
    {
        preg_match('/\((\d+)\)/', $typeStr, $matches);

        return (int)$matches[1];
    }
}
