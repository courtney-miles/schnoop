<?php

namespace MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType;

use MilesAsylum\SchnoopSchema\MySQL\DataType\TimeType;

class TimeTypeFactory extends AbstractTimeTypeFactory
{
    /**
     * @param $typeStr
     * @param null $collation
     * @return TimeType|bool
     */
    public function createType($typeStr, $collation = null)
    {
        if (!$this->doRecognise($typeStr)) {
            return false;
        }

        $timeType = new TimeType();
        $timeType->setPrecision($this->getPrecision($typeStr));

        return $timeType;
    }

    /**
     * @param $typeStr
     * @return bool
     */
    public function doRecognise($typeStr)
    {
        return preg_match('/^time(\(\d+\))?$/i', $typeStr) === 1;
    }
}
