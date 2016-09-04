<?php

namespace MilesAsylum\Schnoop\SchemaAdapter;

use MilesAsylum\Schnoop\Schnoop;
use MilesAsylum\SchnoopSchema\MySQL\Database\DatabaseInterface;

class DatabaseAdapter implements DatabaseAdapterInterface
{
    /**
     * @var DatabaseInterface
     */
    private $database;
    /**
     * @var Schnoop
     */
    private $schnoop;

    public function __construct(DatabaseInterface $database, Schnoop $schnoop)
    {
        $this->database = $database;
        $this->schnoop = $schnoop;
    }

    public function getName()
    {
        return $this->database->getName();
    }

    public function getDefaultCollation()
    {
        return $this->database->getDefaultCollation();
    }

    public function hasDefaultCollation()
    {
        return $this->database->hasDefaultCollation();
    }

    public function setDefaultCollation($defaultCollation)
    {
        return $this->database->setDefaultCollation($defaultCollation);
    }

    public function getTableList()
    {
        return $this->schnoop->getTableList($this->database->getName());
    }

    public function getTable($tableName)
    {
        return $this->schnoop->getTable($this->database->getName(), $tableName);
    }

    public function __toString()
    {
        return (string)$this->database;
    }
}
