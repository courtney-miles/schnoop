<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 21/06/16
 * Time: 10:08 PM
 */

namespace MilesAsylum\Schnoop\PHPUnit\Framework\Constraint;

use MilesAsylum\Schnoop\Schema\MySQL\DataType\StringTypeInterface;

class IsStringTypeConstruct extends AbstractSchnoopTestConstraint
{
    /**
     * @var string
     */
    protected $type;

    /**
     * @var int
     */
    protected $length;
    
    /**
     * @var string
     */
    protected $characterSet;
    
    /**
     * @var string
     */
    protected $collation;

    /**
     * @var bool
     */
    protected $allowDefault;

    public function __construct($type, $length, $characterSet, $collation, $allowDefault)
    {
        parent::__construct();

        $this->type = $type;
        $this->length = $length;
        $this->characterSet = $characterSet;
        $this->collation = $collation;
        $this->allowDefault = $allowDefault;
    }

    /**
     * @param StringTypeInterface $other
     * @return bool
     */
    public function matches($other)
    {
        if ($this->type !== $other->getType()) {
            $this->setFailure(
                'type',
                $this->type,
                $other->getType(),
                'string has correct type'
            );

            return false;
        }

        if ($this->length !== $other->getLength()) {
            $this->setFailure(
                'length',
                $this->length,
                $other->getLength(),
                'string has correct length'
            );
            
            return false;
        }

        if ($this->characterSet !== $other->getCharacterSet()) {
            $this->setFailure(
                'characterSet',
                $this->characterSet,
                $other->getCharacterSet(),
                'string has correct characterSet'
            );

            return false;
        }

        if ($this->collation !== $other->getCollation()) {
            $this->setFailure(
                'collation',
                $this->collation,
                $other->getCollation(),
                'string has correct collation'
            );

            return false;
        }

        if ($this->allowDefault !== $other->allowDefault()) {
            $this->setFailure(
                'allowDefault',
                $this->allowDefault,
                $other->allowDefault(),
                'string has correct allowDefault'
            );

            return false;
        }
        
        return true;
    }
}