<?php

namespace MilesAsylum\Schnoop\Tests\Schnoop\SchemaFactory\MySQL\Database;

use MilesAsylum\Schnoop\PHPUnit\Framework\TestMySQLCase;
use MilesAsylum\Schnoop\SchemaAdapter\MySQL\Database;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\Database\DatabaseFactory;
use PHPUnit\Framework\MockObject\MockObject;

class DatabaseFactoryTest extends TestMySQLCase
{
    /**
     * @var DatabaseFactory
     */
    protected $databaseMapper;

    public function setUp(): void
    {
        parent::setUp();

        $this->databaseMapper = new DatabaseFactory($this->getConnection());
    }

    public function testNewDatabase()
    {
        $databaseName = 'schnoop_db';
        $database = $this->databaseMapper->newDatabase($databaseName);

        $this->assertInstanceOf(Database::class, $database);
        $this->assertSame($databaseName, $database->getName());
    }

    public function testFetchRaw()
    {
        $expectedRaw = [
            'schema_name' => $this->getDatabaseName(),
            'default_collation_name' => 'utf8mb4_unicode_ci',
        ];

        $this->assertSame($expectedRaw, $this->databaseMapper->fetchRaw($this->getDatabaseName()));
    }

    public function testCreateFromRaw()
    {
        $raw = [
            'schema_name' => 'schnoop_db',
            'default_collation_name' => 'utf8mb4_unicode_ci',
        ];

        $mockDatabase = $this->createMock(Database::class);

        $mockDatabase->expects($this->once())
            ->method('setDefaultCollation')
            ->with($raw['default_collation_name']);

        /** @var DatabaseFactory|MockObject $mockDatabaseMapper */
        $mockDatabaseMapper = $this->getMockBuilder(DatabaseFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['newDatabase'])
            ->getMock();

        $mockDatabaseMapper->expects($this->once())
            ->method('newDatabase')
            ->with($raw['schema_name'])
            ->willReturn($mockDatabase);

        $this->assertSame($mockDatabase, $mockDatabaseMapper->createFromRaw($raw));
    }

    public function testFetch()
    {
        $databaseName = 'schnoop_db';
        $raw = ['foo'];

        $mockDatabase = $this->createMock(Database::class);

        /** @var DatabaseFactory|MockObject $mockDatabaseMapper */
        $mockDatabaseMapper = $this->getMockBuilder(DatabaseFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['fetchRaw', 'createFromRaw'])
            ->getMock();

        $mockDatabaseMapper->expects($this->once())
            ->method('fetchRaw')
            ->with($databaseName)
            ->willReturn($raw);

        $mockDatabaseMapper->expects($this->once())
            ->method('createFromRaw')
            ->with($raw)
            ->willReturn($mockDatabase);

        $this->assertSame($mockDatabase, $mockDatabaseMapper->fetch($databaseName));
    }
}
