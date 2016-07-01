<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 19/06/16
 * Time: 3:52 PM
 */

namespace MilesAsylum\Schnoop\Schema\MySQL\Column;

use MilesAsylum\Schnoop\Schema\AbstractCommonColumn;
use MilesAsylum\Schnoop\Schema\MySQL\DataType\DataTypeInterface;

/**
 * Class Column
 * @package MilesAsylum\Schnoop\Schema\MySQL\Column
 * @method DataTypeInterface getDataType
 */
class Column extends AbstractCommonColumn implements ColumnInterface
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var bool
     */
    protected $allowNull;

    /**
     * @var mixed
     */
    protected $default;

    /**
     * @var string
     */
    protected $comment;

    public function __construct($name, DataTypeInterface $dataType, $allowNull, $default, $comment)
    {
        parent::__construct($name, $dataType);
        $this->allowNull = $allowNull;
        $this->setDefault($default);
        $this->comment = $comment;
    }

    /**
     * @return boolean
     */
    public function isAllowNull()
    {
        return $this->allowNull;
    }

    public function hasDefault()
    {
        if (!$this->getDataType()->allowDefault()) {
            return false;
        }
        
        return $this->default !== null || $this->isAllowNull();
    }

    public function getDefault()
    {
        return $this->default;
    }

    /**
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    protected function setDefault($default)
    {
        if ($default !== null && !$this->getDataType()->allowDefault()) {
            trigger_error(
                'Attempt made to set a default for a data-type that does not support. The supplied default value has been ignored.',
                E_USER_WARNING
            );
        }

        $this->default = $default !== null ? $this->getDataType()->cast($default) : $default;
    }
}