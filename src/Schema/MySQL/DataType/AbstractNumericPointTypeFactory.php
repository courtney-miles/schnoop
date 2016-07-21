<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 19/07/16
 * Time: 7:31 AM
 */

namespace MilesAsylum\Schnoop\Schema\MySQL\DataType;

abstract class AbstractNumericPointTypeFactory implements DataTypeFactoryInterface
{
    protected static function matchPointPattern($pattern, $typeStr)
    {
        $r = preg_match($pattern, $typeStr);

        if ($r === false) {
            throw new \RuntimeException('Error evaluating regular expression:' . preg_last_error());
        } elseif ($r === 1) {
            $r = preg_match('/(\(\d+,\d+?\))?( unsigned)?$/i', $typeStr);
        }

        return (bool)$r;
    }

    /**
     * @param string $typeStr
     * @return array The array will contain two values. The first item is the
     * precision, and the second is the scale. In the case that a precision
     * and scale is not specified, both items will be null.
     */
    protected static function getPrecisionScale($typeStr)
    {
        $precision = $scale = null;

        if (preg_match('/\((\d+),(\d+)\)/', $typeStr, $matches)) {
            $precision = (int)$matches[1];
            $scale = (int)$matches[2];
        }

        return [$precision, $scale];
    }

    protected static function getSigned($typeStr)
    {
        return stripos($typeStr, ' unsigned') === false;
    }
}
