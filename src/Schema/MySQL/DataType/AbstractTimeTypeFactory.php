<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 21/07/16
 * Time: 7:27 AM
 */

namespace MilesAsylum\Schnoop\Schema\MySQL\DataType;

abstract class AbstractTimeTypeFactory implements DataTypeFactoryInterface
{
    protected static function getPrecision($typeStr)
    {
        $precision = 0;

        if (preg_match('/\((\d+)\)$/', $typeStr, $matches)) {
            $precision = (int)$matches[1];
        }

        return (int)$precision;
    }
}
