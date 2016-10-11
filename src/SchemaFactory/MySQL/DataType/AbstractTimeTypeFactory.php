<?php

namespace MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType;

use MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType\DataTypeFactoryInterface;

abstract class AbstractTimeTypeFactory implements DataTypeFactoryInterface
{
    /**
     * Extract the precision from the time type string.
     * @param string $typeStr
     * @return int Precision
     */
    protected function extractPrecision($typeStr)
    {
        $precision = 0;

        if (preg_match('/\((\d+)\)$/', $typeStr, $matches)) {
            $precision = (int)$matches[1];
        }

        return (int)$precision;
    }
}
