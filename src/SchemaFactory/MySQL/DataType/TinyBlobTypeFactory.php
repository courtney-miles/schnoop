<?php

namespace MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType;

use MilesAsylum\SchnoopSchema\MySQL\DataType\TinyBlobType;

class TinyBlobTypeFactory implements DataTypeFactoryInterface
{
    public function createType($typeStr, $collation = null)
    {
        if (!$this->doRecognise($typeStr)) {
            return false;
        }

        return new TinyBlobType();
    }

    public function doRecognise($typeStr)
    {
        return 1 === preg_match('/^tinyblob$/i', $typeStr);
    }
}
