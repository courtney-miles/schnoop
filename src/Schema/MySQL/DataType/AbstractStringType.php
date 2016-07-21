<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 20/06/16
 * Time: 4:29 PM
 */

namespace MilesAsylum\Schnoop\Schema\MySQL\DataType;

use MilesAsylum\Schnoop\Schema\MySQL\DataType\Option\CollationTrait;

abstract class AbstractStringType implements StringTypeInterface
{
    use CollationTrait;
    
    /**
     * @var int
     */
    protected $length;

    public function __construct($length, $collation = null)
    {
        $this->setLength($length);
        $this->setCollation($collation);
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