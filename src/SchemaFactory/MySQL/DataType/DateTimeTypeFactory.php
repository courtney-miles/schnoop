<?php

namespace MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType;

use MilesAsylum\SchnoopSchema\MySQL\DataType\DateTimeType;

class DateTimeTypeFactory extends AbstractTimeTypeFactory
{
    /**
     * {@inheritdoc}
     */
    public function createType($typeStr, $collation = null)
    {
        if (!$this->doRecognise($typeStr)) {
            return false;
        }

        $dataTimeType = new DateTimeType();
        $dataTimeType->setPrecision($this->extractPrecision($typeStr));

        return $dataTimeType;
    }

    /**
     * {@inheritdoc}
     */
    public function doRecognise($typeStr)
    {
        return 1 === preg_match('/^datetime(\(\d\))?$/i', $typeStr);
    }
}
