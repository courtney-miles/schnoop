<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 19/06/16
 * Time: 4:23 PM
 */

namespace MilesAsylum\Schnoop\Schema\MySQL\Column;

use MilesAsylum\Schnoop\Schema\MySQL\DataType\NumericTypeInterface;

class NumericColumn extends Column implements NumericColumnInterface
{
    /**
     * @var bool
     */
    protected $zeroFill;

    /**
     * @var bool
     */
    protected $autoIncrement;
    
    /**
     * IntColumn constructor.
     * @param string $name
     * @param NumericTypeInterface $dataType
     * @param bool $zeroFill
     * @param bool $allowNull
     * @param int $default
     * @param bool $autoIncrement
     * @param string $comment
     */
    public function __construct(
        $name,
        NumericTypeInterface $dataType,
        $zeroFill,
        $allowNull,
        $default,
        $autoIncrement,
        $comment
    ) {
        parent::__construct($name, $dataType, $allowNull, $default, $comment);
        $this->setZeroFill($zeroFill);
        $this->setAutoIncrement($autoIncrement);
    }

    /**
     * @return boolean
     */
    public function isZeroFill()
    {
        return $this->zeroFill;
    }

    /**
     * @return boolean
     */
    public function isAutoIncrement()
    {
        return $this->autoIncrement;
    }


    /**
     * @param boolean $zeroFill
     */
    protected function setZeroFill($zeroFill)
    {
        $this->zeroFill = $zeroFill;
    }

    /**
     * @param boolean $autoIncrement
     */
    protected function setAutoIncrement($autoIncrement)
    {
        $this->autoIncrement = $autoIncrement;
    }
}