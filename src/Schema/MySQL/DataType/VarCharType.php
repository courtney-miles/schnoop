<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 20/06/16
 * Time: 8:46 PM
 */

namespace MilesAsylum\Schnoop\Schema\MySQL\DataType;

class VarCharType extends AbstractStringType
{
    public function __construct($length, $characterSet, $collation)
    {
        parent::__construct($length, $characterSet, $collation);
    }

    public function getType()
    {
        return self::TYPE_VARCHAR;
    }
    
    public function allowDefault()
    {
        return true;
    }
}