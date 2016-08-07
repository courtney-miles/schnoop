<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 27/06/16
 * Time: 7:20 AM
 */

namespace MilesAsylum\Schnoop\Schema\MySQL\Database;

interface DatabaseInterface extends \MilesAsylum\Schnoop\Schema\DatabaseInterface
{
    public function getDefaultCollation();

    public function hasDefaultCollation();

    public function __toString();
}
