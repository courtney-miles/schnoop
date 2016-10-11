<?php

namespace MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType;

use MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType\DataTypeFactoryInterface;
use MilesAsylum\SchnoopSchema\MySQL\DataType\NumericPointTypeInterface;

interface NumericPointTypeFactoryInterface extends DataTypeFactoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function populate(NumericPointTypeInterface $numericPointType, $typeStr);

    /**
     * {@inheritdoc}
     */
    public function newType();
}
