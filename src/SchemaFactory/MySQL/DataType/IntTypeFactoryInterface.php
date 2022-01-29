<?php

namespace MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType;

use MilesAsylum\SchnoopSchema\MySQL\DataType\IntTypeInterface;

interface IntTypeFactoryInterface extends DataTypeFactoryInterface
{
    /**
     * Create a new integer data type.
     *
     * @return IntTypeInterface
     */
    public function newType();

    /**
     * Populate integer data type properties from the supplied type string.
     *
     * @param string $typeStr
     *
     * @return IntTypeInterface
     */
    public function populate(IntTypeInterface $intType, $typeStr);
}
