<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 1/07/16
 * Time: 7:10 AM
 */

namespace MilesAsylum\Schnoop\Schema\MySQL\DataType;

abstract class AbstractTextType extends AbstractStringType implements TextTypeInterface
{
    public function doesAllowDefault()
    {
        return false;
    }
}
