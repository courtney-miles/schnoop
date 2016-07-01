<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 26/06/16
 * Time: 5:05 PM
 */

namespace MilesAsylum\Schnoop\Schema\MySQL\DataType;

abstract class AbstractBinaryType implements BinaryTypeInterface
{
    /**
     * @var int
     */
    private $length;

    /**
     * AbstractBinaryType constructor.
     * @param int $length
     */
    public function __construct($length)
    {
        $this->setLength($length);
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
     * @param int $length
     */
    protected function setLength($length)
    {
        $this->length = (int)$length;
    }
}