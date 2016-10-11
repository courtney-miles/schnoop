<?php

namespace MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType;

use MilesAsylum\SchnoopSchema\MySQL\DataType\DataTypeInterface;

interface DataTypeFactoryInterface
{
    /**
     * Create a data type object from the supplied string.
     * @param $typeStr
     * @param null $collation The collation for the data type.  Supply only if the intended type supports collation.
     * @return DataTypeInterface|bool
     */
    public function createType($typeStr, $collation = null);

    /**
     * Identify if the factory recognises the supplied type string and can construct a data type object from it.
     * @param $typeStr
     * @return bool
     */
    public function doRecognise($typeStr);
}
