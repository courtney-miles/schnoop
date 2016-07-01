<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 20/06/16
 * Time: 4:30 PM
 */

namespace MilesAsylum\Schnoop\Schema\MySQL\DataType;

use MilesAsylum\Schnoop\Schema\MySQL\DataType\Option\LengthInterface;

interface StringTypeInterface extends DataTypeInterface, LengthInterface
{
    /**
     * @return string
     */
    public function getCharacterSet();

    /**
     * @return string
     */
    public function getCollation();
}