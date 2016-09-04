<?php

namespace MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType;

use MilesAsylum\Schnoop\SchemaFactory\DataTypeFactoryInterface;
use MilesAsylum\SchnoopSchema\MySQL\DataType\YearType;

class YearTypeFactory implements DataTypeFactoryInterface
{
    /**
     * @param $typeStr
     * @param null $collation
     * @return YearType|bool
     */
    public function createType($typeStr, $collation = null)
    {
        if (!$this->doRecognise($typeStr)) {
            return false;
        }

        return new YearType();
    }

    /**
     * @param $typeStr
     * @return bool
     */
    public function doRecognise($typeStr)
    {
        return strcasecmp($typeStr, 'year') === 0;
    }
}
