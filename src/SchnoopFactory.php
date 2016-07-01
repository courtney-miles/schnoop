<?php

namespace MilesAsylum\Schnoop;

use MilesAsylum\Schnoop\DbInspector\DbInspectorInterface;
use MilesAsylum\Schnoop\DbInspector\MySQLInspector;
use MilesAsylum\Schnoop\Exception\SchnoopException;
use PDO;
use MilesAsylum\Schnoop\Schema\FactoryInterface;
use MilesAsylum\Schnoop\Schema\MySQLFactory;

class SchnoopFactory
{
    /**
     * @param PDO $pdo
     * @return Schnoop
     */
    public static function create(PDO $pdo)
    {
        $dbAdapter = self::newDBInspector($pdo);
        $schemaFactory = self::newSchemaFactory($dbAdapter, $pdo);
        
        return new Schnoop($pdo, $dbAdapter, $schemaFactory);
    }

    /**
     * @param PDO $pdo
     * @return DbInspectorInterface
     */
    public static function newDBInspector(PDO $pdo)
    {
        $dbInspector = null;

        $dbDriver = $pdo->getAttribute(PDO::ATTR_DRIVER_NAME);

        switch ($dbDriver) {
            case 'mysql':
                $dbInspector = new MySQLInspector($pdo);
                break;
            default:
                throw new SchnoopException("The database engine, $dbDriver, is currently not supported.");
                break;
        }

        return $dbInspector;
    }

    /**
     * @param DbInspectorInterface $dbAdapter
     * @param PDO $pdo
     * @return FactoryInterface
     */
    public static function newSchemaFactory(DbInspectorInterface $dbAdapter, PDO $pdo)
    {
        $schemaFactory = null;

        if ($dbAdapter instanceof MySQLInspector) {
            $schemaFactory = new MySQLFactory($pdo);
        }

        return $schemaFactory;
    }
}