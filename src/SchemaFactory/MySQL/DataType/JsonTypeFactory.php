<?php

declare(strict_types=1);

namespace MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType;

use MilesAsylum\SchnoopSchema\MySQL\DataType\JsonType;

class JsonTypeFactory implements DataTypeFactoryInterface
{
    public function createType($typeStr, $collation = null)
    {
        if (!$this->doRecognise($typeStr)) {
            return false;
        }

        return new JsonType();
    }

    public function doRecognise($typeStr)
    {
        return preg_match('/^json$/i', $typeStr) === 1;
    }
}