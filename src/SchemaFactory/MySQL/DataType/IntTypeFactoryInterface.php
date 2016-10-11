<?php

namespace MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType;

use MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType\DataTypeFactoryInterface;
use MilesAsylum\SchnoopSchema\MySQL\DataType\IntTypeInterface;

interface IntTypeFactoryInterface extends DataTypeFactoryInterface
{
    /**
     * Create a new integer data type.
     * @return IntTypeInterface
     */
    public function newType();

    /**
     * Populate integer data type properties from the supplied type string.
     * @param IntTypeInterface $intType
     * @param string $typeStr
     * @return IntTypeInterface
     */
    public function populate(IntTypeInterface $intType, $typeStr);
}
