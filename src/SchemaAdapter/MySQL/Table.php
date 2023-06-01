<?php

namespace MilesAsylum\Schnoop\SchemaAdapter\MySQL;

use MilesAsylum\Schnoop\Schnoop;

class Table extends \MilesAsylum\SchnoopSchema\MySQL\Table\Table implements TableInterface
{
    /**
     * @var Schnoop
     */
    private $schnoop;

    /**
     * Table constructor.
     *
     * @param string $databaseName
     * @param string $name
     */
    public function __construct($databaseName, $name)
    {
        parent::__construct($name);
        $this->setDatabaseName($databaseName);
    }

    public function setSchnoop(Schnoop $schnoop)
    {
        $this->schnoop = $schnoop;
    }

    public function getTriggers()
    {
        return $this->schnoop->getTriggers($this->getName(), $this->getDatabaseName());
    }

    public function hasTriggers()
    {
        return $this->schnoop->hasTriggers($this->getName(), $this->getDatabaseName());
    }
}
