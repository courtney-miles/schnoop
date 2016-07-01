<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 27/06/16
 * Time: 7:20 AM
 */

namespace MilesAsylum\Schnoop\Schema\MySQL\Database;

use MilesAsylum\Schnoop\Schema\CommonDatabaseInterface;

interface DatabaseInterface extends CommonDatabaseInterface
{
    public function getDefaultCharacterSet();

    public function getDefaultCollation();
    
    public function getTableList();
    
    public function hasTable($tableName);
    
    public function getTable($tableName);
}