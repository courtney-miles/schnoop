<?php

namespace MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType;

use MilesAsylum\Schnoop\SchemaFactory\DataTypeFactoryInterface;

abstract class AbstractTimeTypeFactory implements DataTypeFactoryInterface
{
    protected function getPrecision($typeStr)
    {
        $precision = 0;

        if (preg_match('/\((\d+)\)$/', $typeStr, $matches)) {
            $precision = (int)$matches[1];
        }

        return (int)$precision;
    }
}
