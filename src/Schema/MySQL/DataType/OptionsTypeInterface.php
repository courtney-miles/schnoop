<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 11/07/16
 * Time: 7:26 AM
 */

namespace MilesAsylum\Schnoop\Schema\MySQL\DataType;

use MilesAsylum\Schnoop\Schema\MySQL\DataType\Option\CollationInterface;
use MilesAsylum\Schnoop\Schema\MySQL\DataType\Option\OptionsInterface;

interface OptionsTypeInterface extends DataTypeInterface, OptionsInterface, CollationInterface
{

}