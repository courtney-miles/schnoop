<?php

namespace MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType;

use MilesAsylum\SchnoopSchema\MySQL\DataType\NumericPointTypeInterface;

abstract class AbstractNumericPointTypeFactory implements NumericPointTypeFactoryInterface
{
    public function createType($typeStr, $collation = null)
    {
        return $this->populate($this->newType(), $typeStr);
    }

    public function populate(NumericPointTypeInterface $numericPointType, $typeStr)
    {
        if (!$this->doRecognise($typeStr)) {
            return false;
        }

        $numericPointType->setSigned($this->extractSigned($typeStr));
        $numericPointType->setPrecisionScale(...$this->extractPrecisionScale($typeStr));
        $numericPointType->setZeroFill($this->extractZeroFill($typeStr));

        return $numericPointType;
    }

    protected function matchPointPattern($pattern, $typeStr)
    {
        $r = preg_match($pattern, $typeStr);

        if ($r === false) {
            throw new \RuntimeException('Error evaluating regular expression:' . preg_last_error());
        } elseif ($r === 1) {
            $r = preg_match('/(\(\d+,\d+?\))?( unsigned)?$/i', $typeStr);
        }

        return (bool)$r;
    }

    /**
     * @param string $typeStr
     * @return array The array will contain two values. The first item is the
     * precision, and the second is the scale. In the case that a precision
     * and scale are not specified in the data-type string, both items will be null.
     */
    protected function extractPrecisionScale($typeStr)
    {
        $precision = $scale = null;

        if (preg_match('/\( *(\d+) *, *(\d+) *\)/', $typeStr, $matches)) {
            $precision = (int)$matches[1];
            $scale = (int)$matches[2];
        }

        return [$precision, $scale];
    }

    /**
     * @param string $typeStr
     * @return bool
     */
    protected function extractSigned($typeStr)
    {
        return stripos($typeStr, ' unsigned') === false;
    }

    /**
     * @param string $typeStr
     * @return bool
     */
    protected function extractZeroFill($typeStr)
    {
        return stripos($typeStr, ' zerofill') !== false;
    }
}
