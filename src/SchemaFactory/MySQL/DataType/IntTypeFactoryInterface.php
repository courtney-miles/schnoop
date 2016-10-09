<?php

namespace MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType;

use MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType\DataTypeFactoryInterface;
use MilesAsylum\SchnoopSchema\MySQL\DataType\IntTypeInterface;

interface IntTypeFactoryInterface extends DataTypeFactoryInterface
{
    /**
     * @return IntTypeInterface
     */
    public function newType();

    /**
     * @param IntTypeInterface $intType
     * @param $typeStr
     * @return IntTypeInterface
     */
    public function populate(IntTypeInterface $intType, $typeStr);
}
