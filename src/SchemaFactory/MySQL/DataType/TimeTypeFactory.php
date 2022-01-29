<?php

namespace MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType;

use MilesAsylum\SchnoopSchema\MySQL\DataType\TimeType;

class TimeTypeFactory extends AbstractTimeTypeFactory
{
    /**
     * {@inheritdoc}
     */
    public function createType($typeStr, $collation = null)
    {
        if (!$this->doRecognise($typeStr)) {
            return false;
        }

        $timeType = new TimeType();
        $timeType->setPrecision($this->extractPrecision($typeStr));

        return $timeType;
    }

    /**
     * {@inheritdoc}
     */
    public function doRecognise($typeStr)
    {
        return 1 === preg_match('/^time(\(\d+\))?$/i', $typeStr);
    }
}
