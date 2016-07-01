<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 20/06/16
 * Time: 9:40 PM
 */

namespace MilesAsylum\Schnoop\PHPUnit\Framework\Constraint;

use MilesAsylum\Schnoop\Schema\MySQL\DataType\IntTypeInterface;

class IsIntTypeConstruct extends AbstractSchnoopTestConstraint
{
    /**
     * @var string
     */
    protected $type;

    /**
     * @var int
     */
    protected $displayWidth;
    
    /**
     * @var bool
     */
    protected $signed;
    
    /**
     * @var int
     */
    protected $minRange;
    
    /**
     * @var int
     */
    protected $maxRange;
    
    protected $allowDefault;

    /**
     * IsSuccessfulIntConstruct constructor.
     * @param int $displayWidth
     * @param bool $signed
     * @param int $minRange
     * @param int $maxRange
     */
    public function __construct($type, $displayWidth, $signed, $minRange, $maxRange, $allowDefault)
    {
        parent::__construct();

        $this->type = $type;
        $this->displayWidth = $displayWidth;
        $this->signed = $signed;
        $this->minRange = $minRange;
        $this->maxRange = $maxRange;
        $this->allowDefault = $allowDefault;
    }

    /**
     * @param IntTypeInterface $other
     * @return bool
     */
    public function matches($other)
    {
        if ($this->type !== $other->getType()) {
            $this->setFailure(
                'type',
                $this->type,
                $other->getDisplayWidth(),
                'int has correct type'
            );

            return false;
        }

        if ($this->displayWidth !== $other->getDisplayWidth()) {
            $this->setFailure(
                'displayWidth',
                $this->displayWidth,
                $other->getDisplayWidth(),
                'int has correct displayWidth'
            );
            
            return false;
        }
        
        if ($this->signed !== $other->isSigned()) {
            $this->setFailure(
                'displayWidth',
                $this->signed,
                $other->isSigned(),
                'int has correct sign'
            );
            
            return false;
        }
        
        if ($this->minRange !== $other->getMinRange()) {
            $this->setFailure(
                'minRange',
                $this->minRange,
                $other->getMinRange(),
                'int has correct minRange'
            );

            return false;
        }
        
        if ($this->maxRange !== $other->getMaxRange()) {
            $this->setFailure(
                'maxRange',
                $this->maxRange,
                $other->getMaxRange(),
                'int has correct maxRange'
            );
            
            return false;
        }
        
        if ($this->allowDefault !== $other->allowDefault()) {
            $this->setFailure(
                'allowDefault',
                $this->allowDefault,
                $other->allowDefault(),
                'int has correct allowDefault'
            );
            
            return false;
        }
        
        return true;
    }
}