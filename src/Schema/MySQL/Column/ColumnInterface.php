<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 19/06/16
 * Time: 4:15 PM
 */

namespace MilesAsylum\Schnoop\Schema\MySQL\Column;

use MilesAsylum\Schnoop\Schema\CommonColumnInterface;

interface ColumnInterface extends CommonColumnInterface
{
    /**
     * @return boolean
     */
    public function isAllowNull();

    /**
     * @return bool
     */
    public function hasDefault();

    /**
     * @return mixed
     */
    public function getDefault();

    /**
     * @return string
     */
    public function getComment();
}