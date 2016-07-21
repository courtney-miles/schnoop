<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 20/06/16
 * Time: 4:41 PM
 */

namespace MilesAsylum\Schnoop\Schema\MySQL\DataType;

class CharType extends AbstractStringType
{
    public function __construct($length, $collation)
    {
        parent::__construct($length, $collation);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return self::TYPE_CHAR;
    }
    
    public function doesAllowDefault()
    {
        return true;
    }
}