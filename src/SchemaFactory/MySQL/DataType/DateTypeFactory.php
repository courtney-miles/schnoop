<?php

namespace MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType;

use MilesAsylum\Schnoop\SchemaFactory\DataTypeFactoryInterface;
use MilesAsylum\SchnoopSchema\MySQL\DataType\DateType;

class DateTypeFactory implements DataTypeFactoryInterface
{
    /**
     * @param $typeStr
     * @param null $collation
     * @return DateType|bool
     */
    public function create($typeStr, $collation = null)
    {
        if (!$this->doRecognise($typeStr)) {
            return false;
        }

        return new DateType();
    }

    /**
     * @param $typeStr
     * @return bool
     */
    public function doRecognise($typeStr)
    {
        return strcasecmp($typeStr, 'date') === 0;
    }
}
