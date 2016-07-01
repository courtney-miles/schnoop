<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 24/06/16
 * Time: 7:23 AM
 */

namespace MilesAsylum\Schnoop\PHPUnit\Framework\Constraint;

use MilesAsylum\Schnoop\Schema\MySQL\Column\ColumnInterface;
use MilesAsylum\Schnoop\Schema\MySQL\DataType\DataTypeInterface;

class IsColumnConstruct extends AbstractSchnoopTestConstraint
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var DataTypeInterface
     */
    protected $dataType;

    /**
     * @var bool
     */
    protected $allowNull;

    /**
     * @var bool
     */
    protected $hasDefault;

    /**
     * @var int|string|float
     */
    protected $default;

    /**
     * @var string
     */
    protected $comment;

    /**
     * IsColumnConstruct constructor.
     * @param string $name
     * @param DataTypeInterface|string $dataType
     * @param bool $allowNull
     * @param bool $hasDefault
     * @param mixed $default
     * @param string $comment
     */
    public function __construct($name, $dataType, $allowNull, $hasDefault, $default, $comment)
    {
        parent::__construct();
        $this->name = $name;
        $this->dataType = $dataType;
        $this->allowNull = $allowNull;
        $this->hasDefault = $hasDefault;
        $this->default = $default;
        $this->comment = $comment;
    }

    /**
     * @param ColumnInterface $other
     * @return bool
     */
    public function matches($other)
    {
        if ($this->name !== $other->getName()) {
            $this->setFailure(
                'name',
                $this->name,
                $other->getName(),
                'column has correct name'
            );

            return false;
        }

        if (!is_scalar($this->dataType)) {
            if ($this->dataType !== $other->getDataType()) {
                $this->setFailure(
                    'dataType',
                    $this->dataType,
                    $other->getDataType(),
                    'column has correct dataType'
                );

                return false;
            }
        } elseif (!($other->getDataType() instanceof $this->dataType)) {
            $this->setFailure(
                'dataType',
                $this->dataType,
                $other->getDataType(),
                'column has correct instance for dataType'
            );

            return false;
        }

        if ($this->allowNull !== $other->isAllowNull()) {
            $this->setFailure(
                'allowNull',
                $this->allowNull,
                $other->isAllowNull(),
                'column has correct allowNull'
            );

            return false;
        }
        
        if ($this->hasDefault !== $other->hasDefault()) {
            $this->setFailure(
                'hasDefault',
                $this->hasDefault,
                $other->hasDefault(),
                'column correctly identifies hasDefault'
            );
        }

        if ($this->default !== $other->getDefault()) {
            $this->setFailure(
                'default',
                $this->default,
                $other->getDefault(),
                'column has correct default'
            );

            return false;
        }

        if ($this->comment !== $other->getComment()) {
            $this->setFailure(
                'comment',
                $this->comment,
                $other->getComment(),
                'column has correct comment'
            );

            return false;
        }

        return true;
    }
}