<?php

namespace MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType;

use MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType\DataTypeFactoryInterface;
use MilesAsylum\SchnoopSchema\MySQL\DataType\CharTypeInterface;

abstract class AbstractCharTypeFactory implements DataTypeFactoryInterface
{
    protected function extractLength($typeStr)
    {
        preg_match('/\((\d+)\)/', $typeStr, $matches);

        return (int)$matches[1];
    }
}
