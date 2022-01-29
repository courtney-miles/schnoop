<?php

namespace MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType;

abstract class AbstractCharTypeFactory implements DataTypeFactoryInterface
{
    /**
     * Extract length from a data type string.
     *
     * @param $typeStr
     *
     * @return int Length
     */
    protected function extractLength($typeStr)
    {
        preg_match('/\((\d+)\)/', $typeStr, $matches);

        return (int) $matches[1];
    }
}
