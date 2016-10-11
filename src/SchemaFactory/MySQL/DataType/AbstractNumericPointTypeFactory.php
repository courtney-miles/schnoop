<?php

namespace MilesAsylum\Schnoop\SchemaFactory\MySQL\DataType;

use MilesAsylum\SchnoopSchema\MySQL\DataType\NumericPointTypeInterface;

abstract class AbstractNumericPointTypeFactory implements NumericPointTypeFactoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function createType($typeStr, $collation = null)
    {
        return $this->populate($this->newType(), $typeStr);
    }

    /**
     * {@inheritdoc}
     */
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

    /**
     * Checks the point type string against the supplied pattern and that it confirms to an expected structure.
     * @param string $pattern I.e. /^decimal/i or /^float/i
     * @param string $typeStr
     * @return bool True if the type string matches the supplied pattern and conforms to an expected structure.
     */
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
     * Extract the precision and scale from the supplied type string.
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
     * Extract if the type string is for an signed integer.
     * @param string $typeStr
     * @return bool True if the type string specifies the number is signed.
     */
    protected function extractSigned($typeStr)
    {
        return stripos($typeStr, ' unsigned') === false;
    }

    /**
     * Extract if the type string is for a zero-filled number.
     * @param string $typeStr
     * @return bool True if the type string specifies zero-filling.
     */
    protected function extractZeroFill($typeStr)
    {
        return stripos($typeStr, ' zerofill') !== false;
    }
}
