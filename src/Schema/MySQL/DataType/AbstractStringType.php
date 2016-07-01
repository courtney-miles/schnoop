<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 20/06/16
 * Time: 4:29 PM
 */

namespace MilesAsylum\Schnoop\Schema\MySQL\DataType;

abstract class AbstractStringType implements StringTypeInterface
{
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

    public function __construct($length, $characterSet = null, $collation = null)
    {
        $this->setLength($length);
        $this->setCharacterSet($characterSet, $collation);
    }

    public function cast($value)
    {
        return (string)$value;
    }

    /**
     * @return int
     */
    public function getLength()
    {
        return $this->length;
    }

    /**
     * @return string
     */
    public function getCharacterSet()
    {
        return $this->characterSet;
    }

    /**
     * @return string
     */
    public function getCollation()
    {
        return $this->collation;
    }

    /**
     * @param string $characterSet
     * @param string $collation
     */
    protected function setCharacterSet($characterSet, $collation)
    {
        $this->characterSet = $characterSet;
        $this->collation = $collation;
    }

    /**
     * @param int $length
     */
    protected function setLength($length)
    {
        $this->length = (int)$length;
    }
}