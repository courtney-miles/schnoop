<?php

namespace MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType;

use MilesAsylum\SchnoopSchema\MySQL\DataType\DateTimeType;

class DateTimeTypeFactory extends AbstractTimeTypeFactory
{
    /**
     * @param $typeStr
     * @param null $collation
     * @return DateTimeType|bool
     */
    public function create($typeStr, $collation = null)
    {
        if (!$this->doRecognise($typeStr)) {
            return false;
        }

        $dataTimeType = new DateTimeType();
        $dataTimeType->setPrecision($this->getPrecision($typeStr));

        return $dataTimeType;
    }

    /**
     * @param $typeStr
     * @return bool
     */
    public function doRecognise($typeStr)
    {
        return preg_match('/^datetime(\(\d\))?$/i', $typeStr) === 1;
    }
}
