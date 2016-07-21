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

    protected $collation = 'utf8_general_ci';

    protected $doesAllowDefault = true;

    public function setUp()
    {
        parent::setUp();

        $this->abstractStringType = $this->getMockForAbstractClass(
            'MilesAsylum\Schnoop\Schema\MySQL\DataType\AbstractStringType',
            [
                $this->length,
                $this->collation
            ]
        );

        $this->abstractStringType->method('getName')
            ->willReturn($this->type);

        $this->abstractStringType->method('doesAllowDefault')
            ->willReturn($this->doesAllowDefault);
    }

    public function testConstructed()
    {
        $this->stringTypeAsserts(
            $this->type,
            $this->length,
            $this->collation,
            $this->doesAllowDefault,
            $this->abstractStringType
        );
    }
}
