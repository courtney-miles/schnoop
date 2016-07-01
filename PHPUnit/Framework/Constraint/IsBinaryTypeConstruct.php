<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 26/06/16
 * Time: 5:22 PM
 */

namespace MilesAsylum\Schnoop\PHPUnit\Framework\Constraint;

use MilesAsylum\Schnoop\Schema\MySQL\DataType\BinaryTypeInterface;

class IsBinaryTypeConstruct extends AbstractSchnoopTestConstraint
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
     * @var bool
     */
    private $allowDefault;

    public function __construct($type, $length, $allowDefault)
    {
        parent::__construct();

        $this->type = $type;
        $this->length = $length;
        $this->allowDefault = $allowDefault;
    }

    /**
     * @param BinaryTypeInterface $other
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