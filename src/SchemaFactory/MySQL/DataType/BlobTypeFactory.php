<?php

namespace MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType;

use MilesAsylum\Schnoop\SchemaFactory\DataTypeFactoryInterface;
use MilesAsylum\SchnoopSchema\MySQL\DataType\BlobType;

class BlobTypeFactory implements DataTypeFactoryInterface
{
    /**
     * @param $typeStr
     * @param null $collation
     * @return BlobType|bool
     */
    public function createType($typeStr, $collation = null)
    {
        if (!$this->doRecognise($typeStr)) {
            return false;
        }

        return new BlobType();
    }

    /**
     * @param $typeStr
     * @return bool
     */
    public function doRecognise($typeStr)
    {
        return preg_match('/^blob$/i', $typeStr) === 1;
    }
}
