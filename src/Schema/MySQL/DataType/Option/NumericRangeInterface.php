<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 18/06/16
 * Time: 6:40 PM
 */

namespace MilesAsylum\Schnoop\Schema\MySQL\DataType\Option;

interface NumericRangeInterface
{
    public function getMinRange();
    
    public function getMaxRange();
}