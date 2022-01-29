<?php

namespace MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType;

use MilesAsylum\SchnoopSchema\MySQL\DataType\TimestampType;

class TimestampTypeFactory extends AbstractTimeTypeFactory
{
    /**
     * {@inheritdoc}
     */
    public function createType($typeStr, $collation = null)
    {
        if (!$this->doRecognise($typeStr)) {
            return false;
        }

        $timestampType = new TimestampType();
        $timestampType->setPrecision($this->extractPrecision($typeStr));

        return $timestampType;
    }

    /**
     * {@inheritdoc}
     */
    public function doRecognise($typeStr)
    {
        return 1 === preg_match('/^timestamp(\(\d+\))?$/i', $typeStr);
    }
}
