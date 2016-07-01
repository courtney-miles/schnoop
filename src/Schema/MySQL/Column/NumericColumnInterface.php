<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 19/06/16
 * Time: 4:38 PM
 */

namespace MilesAsylum\Schnoop\Schema\MySQL\Column;

interface NumericColumnInterface extends ColumnInterface
{
    /**
     * @return bool
     */
    public function isZeroFill();

    /**
     * @return bool
     */
    public function isAutoIncrement();
}