<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 21/06/16
 * Time: 10:20 PM
 */

namespace MilesAsylum\Schnoop\PHPUnit\Framework\Constraint;


abstract class AbstractSchnoopTestConstraint extends \PHPUnit_Framework_Constraint
{
    protected $failDescription;

    protected $failField;

    protected $failExpected;

    protected $failActual;
    
    protected function setFailure($field, $expected, $actual, $description)
    {
        $this->failField = $field;
        $this->failActual = $actual;
        $this->failExpected = $expected;
        $this->failDescription = $description;
    }

    protected function failureDescription($other)
    {
        return sprintf(
            '%s is equal to expected %s for constructed %s',
            $this->exporter->export($this->failActual),
            $this->exporter->export($this->failExpected),
            $this->failField
        );
    }

    protected function additionalFailureDescription($other)
    {
        return $this->exporter->export($other);
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString()
    {
        return $this->failDescription;
    }
}