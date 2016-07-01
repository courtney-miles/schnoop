<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 21/06/16
 * Time: 7:10 AM
 */

namespace MilesAsylum\Schnoop\PHPUnit\Framework\Constraint;

use MilesAsylum\Schnoop\Schema\MySQL\DataType\NumericPointTypeInterface;

class IsNumericPointTypeConstruct extends AbstractSchnoopTestConstraint
{
    /**
     * @var string
     */
    protected $type;

    /**
     * @var int
     */
    protected $precision;
    
    /**
     * @var int
     */
    protected $scale;
    
    /**
     * @var bool
     */
    protected $signed;

    /**
     * @var string
     */
    protected $minRange;
    
    /**
     * @var string
     */
    protected $maxRange;
    
    /**
     * @var bool
     */
    protected $allowDefault;

    /**
     * IsSuccessfulNumericPointConstruct constructor.
     * @param int $precision
     * @param int $scale
     * @param bool $signed
     * @param string $minRange
     * @param string $maxRange
     */
    public function __construct($type, $precision, $scale, $signed, $minRange, $maxRange, $allowDefault)
    {
        parent::__construct();

        $this->type = $type;
        $this->precision = $precision;
        $this->scale = $scale;
        $this->signed = $signed;
        $this->minRange = $minRange;
        $this->maxRange = $maxRange;
        $this->allowDefault = $allowDefault;
    }

    /**
     * @param NumericPointTypeInterface $other
     */
    public function matches($other)
    {
        if ($this->type !== $other->getType()) {
            $this->setFailure(
                'type',
                $this->type,
                $other->getType(),
                'numeric has correct type'
            );

            return false;
        }

        if ($this->precision !== $other->getPrecision()) {
            $this->setFailure(
                'precision',
                $this->precision,
                $other->getPrecision(),
                'numeric has correct precision'
            );
            
            return false;
        }

        if ($this->scale !== $other->getScale()) {
            $this->setFailure(
                'scale',
                $this->scale,
                $other->getScale(),
                'numeric has correct scale'
            );
            
            return false;
        }

        if ($this->signed !== $other->isSigned()) {
            $this->setFailure(
                'signed',
                $this->signed,
                $other->isSigned(),
                'numeric has correct sign'
            );
            
            return false;
        }

        if ($this->minRange !== $other->getMinRange()) {
            $this->setFailure(
                'minRange',
                $this->minRange,
                $other->getMinRange(),
                'numeric has correct minRange'
            );
            
            return false;
        }

        if ($this->maxRange !== $other->getMaxRange()) {
            $this->setFailure(
                'maxRange',
                $this->maxRange,
                $other->getMaxRange(),
                'numeric has correct maxRange'
            );
            
            return false;
        }

        if ($this->allowDefault !== $other->allowDefault()) {
            $this->setFailure(
                'allowDefault',
                $this->allowDefault,
                $other->allowDefault(),
                'numeric has correct allowDefault'
            );

            return false;
        }
        
        return true;
    }
}