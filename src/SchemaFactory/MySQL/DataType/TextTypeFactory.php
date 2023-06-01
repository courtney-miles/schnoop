<?php

namespace MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType;

use MilesAsylum\SchnoopSchema\MySQL\DataType\TextType;

class TextTypeFactory implements DataTypeFactoryInterface
{
    public function createType($typeStr, $collation = null)
    {
        if (!$this->doRecognise($typeStr)) {
            return false;
        }

        $textType = new TextType();
        $textType->setCollation($collation);

        return $textType;
    }

    public function doRecognise($typeStr)
    {
        return 1 === preg_match('/^text$/i', $typeStr);
    }
}
