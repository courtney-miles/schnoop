<?php

namespace MilesAsylum\Schnoop\Tests\Schnoop\SchemaFactory\MySQL\Trigger;

use MilesAsylum\Schnoop\PHPUnit\Framework\TestMySQLCase;
use MilesAsylum\Schnoop\PHPUnit\Schnoop\MockPdo;
use MilesAsylum\Schnoop\SchemaAdapter\MySQL\Trigger;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\SetVar\SqlModeFactory;
use MilesAsylum\Schnoop\SchemaFactory\MySQL\Trigger\TriggerFactory;
use MilesAsylum\SchnoopSchema\MySQL\SetVar\SqlMode;
use PHPUnit\Framework\MockObject\MockObject;

class TriggerFactoryTest extends TestMySQLCase
{
    /**
     * @var TriggerFactory
     */
    protected $triggerMapper;

    /**
     * @var SqlModeFactory|MockObject
     */
    protected $mockSqlModeFactory;

    protected $triggerName = 'schnoop_trigger';

    protected $tableName = 'schnoop_tbl';

    protected $databaseName;

    protected $definer;

    public function setUp()
    {
        parent::setUp();

        $this->databaseName = $this->getDatabaseName();

        if ('localhost' === getenv('TESTS_SCHNOOP_DBADAPTER_MYSQL_HOST')) {
            // In Travis, the hostname is resolved explicitly.
            $this->definer = $this->getDatabaseUser().'@localhost';
        } else {
            $this->definer = $this->getDatabaseUser().'@%';
        }

        $this->getConnection()->exec(<<<SQL
DROP TRIGGER IF EXISTS `$this->databaseName`.`{$this->triggerName}`
SQL
        );

        $this->getConnection()->exec(<<<SQL
DROP TABLE IF EXISTS `$this->databaseName`.`{$this->tableName}`
SQL
        );

        $this->getConnection()->exec(<<<SQL
CREATE TABLE `$this->databaseName`.`{$this->tableName}`(
  id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY
)
SQL
        );

        $this->getConnection()->exec(<<<SQL
CREATE TRIGGER `$this->databaseName`.`{$this->triggerName}` AFTER INSERT ON `{$this->tableName}` FOR EACH ROW BEGIN
  DECLARE id INT;
  SELECT 1 INTO id;
END
SQL
        );

        $this->mockSqlModeFactory = $this->createMock(SqlModeFactory::class);

        $this->triggerMapper = new TriggerFactory($this->getConnection(), $this->mockSqlModeFactory);
    }

    public function testFetchRaw()
    {
        $expectedRaw = [
            [
                'Trigger' => $this->triggerName,
                'Event' => 'INSERT',
                'Table' => $this->tableName,
                'Statement' => 'BEGIN
  DECLARE id INT;
  SELECT 1 INTO id;
END',
                'Timing' => 'AFTER',
                'sql_mode' => $this->sqlMode,
                'Definer' => $this->definer,
            ],
        ];

        $this->assertSame(
            $expectedRaw,
            $this->triggerMapper->fetchRaw($this->tableName, $this->databaseName)
        );
    }

    public function testCreateFromRaw()
    {
        $trimmedStatement = <<<SQL
DECLARE id INT;
SELECT 1 INTO id;
SQL;

        $raw = [
            'Trigger' => $this->triggerName,
            'Event' => 'INSERT',
            'Table' => $this->tableName,
            'Statement' => "BEGIN
{$trimmedStatement}
END",
            'Timing' => 'AFTER',
            'sql_mode' => '',
            'Definer' => $this->definer,
        ];

        $mockTrigger = $this->createMock(Trigger::class);
        $mockSqlMode = $this->createMock(SqlMode::class);
        $mockSqlModeFactory = $this->createMock(SqlModeFactory::class);

        $mockSqlModeFactory->expects($this->once())
            ->method('newSqlMode')
            ->with($raw['sql_mode'])
            ->willReturn($mockSqlMode);

        /** @var TriggerFactory|MockObject $triggerMapper */
        $triggerMapper = $this->getMockBuilder(TriggerFactory::class)
            ->setConstructorArgs([$this->createMock(MockPdo::class), $mockSqlModeFactory])
            ->setMethods(['newTrigger'])
            ->getMock();

        $triggerMapper->expects($this->once())
            ->method('newTrigger')
            ->with($raw['Trigger'], $raw['Timing'], $raw['Event'], $raw['Table'])
            ->willReturn($mockTrigger);

        $mockTrigger->expects($this->once())
            ->method('setDefiner')
            ->with($raw['Definer']);
        $mockTrigger->expects($this->once())
            ->method('setBody')
            ->with($trimmedStatement);
        $mockTrigger->expects($this->once())
            ->method('setDatabaseName')
            ->with($this->databaseName);
        $mockTrigger->expects($this->once())
            ->method('setSqlMode')
            ->with($mockSqlMode);

        $this->assertSame([$mockTrigger], $triggerMapper->createFromRaw([$raw], $this->databaseName));
    }

    public function testFetch()
    {
        $expectedRaw = ['foo'];
        $mockTrigger = $this->createMock(Trigger::class);

        /** @var TriggerFactory|MockObject $triggerMapper */
        $triggerMapper = $this->getMockBuilder(TriggerFactory::class)
            ->setConstructorArgs([$this->createMock(MockPdo::class), $this->createMock(SqlModeFactory::class)])
            ->setMethods(['fetchRaw', 'createFromRaw'])
            ->getMock();

        $triggerMapper->expects($this->once())
            ->method('fetchRaw')
            ->with($this->tableName, $this->databaseName)
            ->willReturn($expectedRaw);
        $triggerMapper->expects($this->once())
            ->method('createFromRaw')
            ->with($expectedRaw)
            ->willReturn($mockTrigger);

        $this->assertSame($mockTrigger, $triggerMapper->fetch($this->tableName, $this->databaseName));
    }

    public function testNewTrigger()
    {
        $name = 'schnoop_trigger';
        $timing = Trigger::TIMING_BEFORE;
        $event = Trigger::EVENT_INSERT;
        $tableName = 'scnoop_tbl';

        $trigger = $this->triggerMapper->newTrigger(
            $name,
            $timing,
            $event,
            $tableName
        );

        $this->assertInstanceOf(Trigger::class, $trigger);
        $this->assertSame($name, $trigger->getName());
        $this->assertSame($timing, $trigger->getTiming());
        $this->assertSame($event, $trigger->getEvent());
        $this->assertSame($tableName, $trigger->getTableName());
    }
}
