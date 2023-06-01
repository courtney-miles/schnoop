<?php

namespace MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType;

use MilesAsylum\SchnoopSchema\MySQL\DataType\BlobType;

class BlobTypeFactory implements DataTypeFactoryInterface
{
    public function createType($typeStr, $collation = null)
    {
        if (!$this->doRecognise($typeStr)) {
            return false;
        }

        return new BlobType();
    }

    public function doRecognise($typeStr)
    {
        return 1 === preg_match('/^blob$/i', $typeStr);
    }
}
