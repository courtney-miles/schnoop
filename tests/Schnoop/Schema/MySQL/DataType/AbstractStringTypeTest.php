<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 22/06/16
 * Time: 4:39 PM
 */

namespace MilesAsylum\Schnoop\Tests\Schnoop\Schema\MySQL\DataType;

use MilesAsylum\Schnoop\PHPUnit\Framework\SchnoopTestCase;
use MilesAsylum\Schnoop\Schema\MySQL\DataType\AbstractStringType;

class AbstractStringTypeTest extends SchnoopTestCase
{
    /**
     * @var AbstractStringType
     */
    protected $abstractStringType;

    protected $type = 'char';

    protected $length = 128;

    protected $characterSet = 'utf8';

    protected $collation = 'utf8_general_ci';

    protected $allowDefault = true;

    public function setUp()
    {
        parent::setUp();

        $this->abstractStringType = $this->getMockForAbstractClass(
            'MilesAsylum\Schnoop\Schema\MySQL\DataType\AbstractStringType',
            [
                $this->length,
                $this->characterSet,
                $this->collation
            ]
        );

        $this->abstractStringType->method('getType')
            ->willReturn($this->type);

        $this->abstractStringType->method('allowDefault')
            ->willReturn($this->allowDefault);
    }

    public function testConstructed()
    {
        $this->assertIsStringTypeConstruct(
            $this->type,
            $this->length,
            $this->characterSet,
            $this->collation,
            $this->allowDefault,
            $this->abstractStringType
        );
    }
}
