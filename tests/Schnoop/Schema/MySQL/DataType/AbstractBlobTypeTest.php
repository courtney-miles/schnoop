<?php

namespace MilesAsylum\Schnoop\Tests\Schnoop\Schema\MySQL\DataType;

use MilesAsylum\Schnoop\PHPUnit\Framework\SchnoopTestCase;
use MilesAsylum\Schnoop\Schema\MySQL\DataType\AbstractBlobType;

class AbstractBlobTypeTest extends SchnoopTestCase
{
    /**
     * @var AbstractBlobType
     */
    protected $abstractBlobType;

    protected $length = 123;

    protected $type = 'foo';

    public function setUp()
    {
        parent::setUp();

        $this->abstractBlobType = $this->getMockForAbstractClass(
            AbstractBlobType::class,
            [$this->length]
        );

        $this->abstractBlobType->method('getType')
            ->willReturn($this->type);
    }

    public function testConstructed()
    {
        $this->binaryTypeAsserts(
            $this->type,
            $this->length,
            false,
            strtoupper($this->type),
            $this->abstractBlobType
        );
    }
}
