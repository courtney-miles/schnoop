<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 4/06/16
 * Time: 6:04 PM
 */

namespace MilesAsylum\Schnoop\Schema;

abstract class AbstractCommonDatabase implements CommonDatabaseInterface
{
    protected $name;

    public function __construct($name)
    {
        $this->setName($name);
    }

    public function getName()
    {
        return $this->name;
    }
    
    protected function setName($name)
    {
        $this->name = $name;
    }
}