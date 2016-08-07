<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 18/07/16
 * Time: 7:10 AM
 */

namespace MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType;

use MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType\DataTypeFactoryInterface;

abstract class AbstractIntTypeFactory implements DataTypeFactoryInterface
{
    protected static function matchIntPattern($pattern, $typeStr)
    {
        $r = preg_match($pattern, $typeStr);

        if ($r === false) {
            throw new \RuntimeException('Error evaluating regular expression:' . preg_last_error());
        } elseif ($r === 1) {
            $r = preg_match('/\(\d+\)( unsigned)?$/i', $typeStr);
        }

        return (bool)$r;
    }
    
    protected static function getDisplayWidth($typeStr)
    {
        preg_match('/\((\d+)\)/', $typeStr, $matches);

        return (int)$matches[1];
    }

    protected static function getSigned($typeStr)
    {
        return stripos($typeStr, ' unsigned') === false;
    }
}