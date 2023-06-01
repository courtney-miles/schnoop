<?php

namespace MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType;

use MilesAsylum\SchnoopSchema\MySQL\DataType\TinyTextType;

class TinyTextTypeFactory implements DataTypeFactoryInterface
{
    public function createType($typeStr, $collation = null)
    {
        if (!$this->doRecognise($typeStr)) {
            return false;
        }

        $tinyTextType = new TinyTextType();
        $tinyTextType->setCollation($collation);

        return $tinyTextType;
    }

    public function doRecognise($typeStr)
    {
        return 1 === preg_match('/^tinytext$/i', $typeStr);
    }
}
