<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 26/06/16
 * Time: 5:11 PM
 */

namespace MilesAsylum\Schnoop\Schema\MySQL\DataType;


class BinaryType extends AbstractBinaryType
{
    /**
     * @return string
     */
    public function getName()
    {
        return self::TYPE_BINARY;
    }
    
    public function doesAllowDefault()
    {
        return true;
    }
}