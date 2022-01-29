<?php

namespace MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType;

use MilesAsylum\SchnoopSchema\MySQL\DataType\YearType;

class YearTypeFactory implements DataTypeFactoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function createType($typeStr, $collation = null)
    {
        if (!$this->doRecognise($typeStr)) {
            return false;
        }

        return new YearType();
    }

    /**
     * {@inheritdoc}
     */
    public function doRecognise($typeStr)
    {
        return 0 === strcasecmp($typeStr, 'year');
    }
}
