<?php

namespace MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType;

use MilesAsylum\SchnoopSchema\MySQL\DataType\MediumTextType;

class MediumTextTypeFactory implements DataTypeFactoryInterface
{
    public function createType($typeStr, $collation = null)
    {
        if (!$this->doRecognise($typeStr)) {
            return false;
        }

        $mediumTextType = new MediumTextType();
        $mediumTextType->setCollation($collation);

        return $mediumTextType;
    }

    public function doRecognise($typeStr)
    {
        return 1 === preg_match('/^mediumtext$/i', $typeStr);
    }
}
