<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 27/06/16
 * Time: 7:14 AM
 */

namespace MilesAsylum\Schnoop\Schema\MySQL\Table;

use MilesAsylum\Schnoop\Schema\CommonTableInterface;

interface TableInterface extends CommonTableInterface
{
    public function getEngine();

    public function getDefaultCollation();

    public function getRowFormat();
    
    public function getComment();
}