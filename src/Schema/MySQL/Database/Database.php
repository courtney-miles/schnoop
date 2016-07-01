<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 4/06/16
 * Time: 6:10 PM
 */

namespace MilesAsylum\Schnoop\Schema\MySQL\Database;

use MilesAsylum\Schnoop\Schema\AbstractCommonDatabase;
use MilesAsylum\Schnoop\Schema\MySQL\Table\Table;
use MilesAsylum\Schnoop\Schnoop;

class Database extends AbstractCommonDatabase implements DatabaseInterface
{
    /**
     * @var string
     */
    protected $defaultCharacterSet;

    /**
     * @var string
     */
    protected $defaultCollation;
    
    /**
     * @var Schnoop
     */
    protected $schnoop;

    /**
     * @var
     */
    protected $tableList = array();

    /**
     * @var Table[]
     */
    protected $loadedTables = array();

    public function __construct($name, $characterSet, $collation, Schnoop $schnoop)
    {
        parent::__construct($name);

        $this->schnoop = $schnoop;

        $this->setDefaultCharacterSet($characterSet);
        $this->setDefaultCollation($collation);
        $this->loadTableList();
    }

    /**
     * @return string
     */
    public function getDefaultCharacterSet()
    {
        return $this->defaultCharacterSet;
    }

    /**
     * @return string
     */
    public function getDefaultCollation()
    {
        return $this->defaultCollation;
    }

    public function getTableList()
    {
        return array_values($this->tableList);
    }

    public function hasTable($tableName)
    {
        return isset($this->tableList[$tableName]);
    }

    public function getTable($tableName)
    {
        if ($this->hasTable($tableName)) {
            if (!isset($this->loadedTables[$tableName])) {
                $this->loadedTables[$tableName] = $this->schnoop->getTable($this->name, $tableName);
            }

            return $this->loadedTables[$tableName];
        }

        return null;
    }

    /**
     * @param string $characterSet
     */
    protected function setDefaultCharacterSet($characterSet)
    {
        $this->defaultCharacterSet = $characterSet;
    }

    /**
     * @param string $collation
     */
    protected function setDefaultCollation($collation)
    {
        $this->defaultCollation = $collation;
    }

    protected function loadTableList()
    {
        $tableList = $this->schnoop->getTableList($this->name);
        $this->tableList = array_combine($tableList, $tableList);
    }
}