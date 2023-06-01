<?php

namespace MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType;

use MilesAsylum\SchnoopSchema\MySQL\DataType\LongBlobType;

class LongBlobTypeFactory implements DataTypeFactoryInterface
{
    public function createType($typeStr, $collation = null)
    {
        if (!$this->doRecognise($typeStr)) {
            return false;
        }

        return new LongBlobType();
    }

    public function doRecognise($typeStr)
    {
        return 1 === preg_match('/^longblob$/i', $typeStr);
    }
}
