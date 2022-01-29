<?php

namespace MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType;

use MilesAsylum\SchnoopSchema\MySQL\DataType\MediumBlobType;

class MediumBlobTypeFactory implements DataTypeFactoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function createType($typeStr, $collation = null)
    {
        if (!$this->doRecognise($typeStr)) {
            return false;
        }

        return new MediumBlobType();
    }

    /**
     * {@inheritdoc}
     */
    public function doRecognise($typeStr)
    {
        return 1 === preg_match('/^mediumblob$/i', $typeStr);
    }
}
