<?php

namespace MilesAsylum\Schnoop\Schema;

use MilesAsylum\Schnoop\Schnoop;
use MilesAsylum\SchnoopSchema\MySQL\Table\Table as SSTable;

class Table extends SSTable implements TableInterface
{
    /**
     * @var Schnoop
     */
    private $schnoop;

    public function __construct($databaseName, $name)
    {
        parent::__construct($name);
        $this->setDatabaseName($databaseName);
    }

    /**
     * {@inheritdoc}
     */
    public function setSchnoop(Schnoop $schnoop)
    {
        $this->schnoop = $schnoop;
    }

    /**
     * {@inheritdoc}
     */
    public function getTriggers()
    {
        return $this->schnoop->getTriggers($this->getDatabaseName(), $this->getName());
    }
}
