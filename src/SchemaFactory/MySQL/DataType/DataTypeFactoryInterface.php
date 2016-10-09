<?php

namespace MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType;

use MilesAsylum\SchnoopSchema\MySQL\DataType\DataTypeInterface;

interface DataTypeFactoryInterface
{
    /**
     * @param $typeStr
     * @param null $collation
     * @return DataTypeInterface|bool
     */
    public function createType($typeStr, $collation = null);

    /**
     * @param $typeStr
     * @return bool
     */
    public function doRecognise($typeStr);
}
