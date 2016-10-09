<?php
namespace MilesAsylum\Schnoop\SchemaFactory\MySQL\Database;

interface DatabaseFactoryInterface extends \MilesAsylum\Schnoop\SchemaFactory\MySQL\DatabaseFactoryInterface
{
    public function fetchRaw($databaseName);

    public function createFromRaw(array $rawDatabase);

    /**
     * {@inheritdoc}
     */
    public function newDatabase($databaseName);
}
