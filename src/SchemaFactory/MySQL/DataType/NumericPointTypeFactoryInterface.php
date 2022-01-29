<?php

namespace MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType;

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
