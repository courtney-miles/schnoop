<?php

namespace MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType;

use MilesAsylum\SchnoopSchema\MySQL\DataType\TimestampType;

class TimestampTypeFactory extends AbstractTimeTypeFactory
{
    /**
     * @param $typeStr
     * @param null $collation
     * @return TimestampType|bool
     */
    public function create($typeStr, $collation = null)
    {
        if (!$this->doRecognise($typeStr)) {
            return false;
        }

        $timestampType = new TimestampType();
        $timestampType->setPrecision($this->getPrecision($typeStr));

        return $timestampType;
    }

    /**
     * @param $typeStr
     * @return bool
     */
    public function doRecognise($typeStr)
    {
        return preg_match('/^timestamp(\(\d+\))?$/i', $typeStr) === 1;
    }
}
