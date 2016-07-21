<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 19/06/16
 * Time: 10:42 AM
 */

namespace MilesAsylum\Schnoop\Tests\Schnoop\Schema\MySQL\DataType;

use MilesAsylum\Schnoop\PHPUnit\Framework\SchnoopTestCase;
use MilesAsylum\Schnoop\Schema\MySQL\DataType\AbstractNumericPointType;

class AbstractNumericPointTypeTest extends SchnoopTestCase
{
    /**
     * @var AbstractNumericPointType
     */
    protected $abstractNumericPointTypeSigned;

    /**
     * @var AbstractNumericPointType
     */
    protected $abstractNumericPointTypeUnsigned;

    protected $type = 'decimal';

    protected $precision = 6;

    protected $scale = 2;

    public function setUp()
    {
        parent::setUp();

        $this->abstractNumericPointTypeSigned = $this->getMockForAbstractClass(
            'MilesAsylum\Schnoop\Schema\MySQL\DataType\AbstractNumericPointType',
            [
                true,
                $this->precision,
                $this->scale
            ]
        );

        $this->abstractNumericPointTypeSigned->expects($this->any())
            ->method('getName')
            ->willReturn($this->type);

        $this->abstractNumericPointTypeUnsigned = $this->getMockForAbstractClass(
            'MilesAsylum\Schnoop\Schema\MySQL\DataType\AbstractNumericPointType',
            [
                false,
                $this->precision,
                $this->scale
            ]
        );

        $this->abstractNumericPointTypeUnsigned->expects($this->any())
            ->method('getName')
            ->willReturn($this->type);
    }

    public function testConstructedSigned()
    {
        $this->numericPointTypeAsserts(
            $this->type,
            true,
            $this->precision,
            $this->scale,
            '-9999.99',
            '9999.99',
            true,
            $this->abstractNumericPointTypeSigned
        );
    }

    public function testConstructedUnsigned()
    {
        $this->numericPointTypeAsserts(
            $this->type,
            false,
            $this->precision,
            $this->scale,
            '0',
            '9999.99',
            true,
            $this->abstractNumericPointTypeUnsigned
        );
    }
}
