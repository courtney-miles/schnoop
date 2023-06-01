<?php

namespace MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType;

use MilesAsylum\SchnoopSchema\MySQL\DataType\LongTextType;

class LongTextTypeFactory implements DataTypeFactoryInterface
{
    public function createType($typeStr, $collation = null)
    {
        if (!$this->doRecognise($typeStr)) {
            return false;
        }

        $longTextType = new LongTextType();
        $longTextType->setCollation($collation);

        return $longTextType;
    }

    public function doRecognise($typeStr)
    {
        return 1 === preg_match('/^longtext$/i', $typeStr);
    }
}
