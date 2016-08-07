<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 12/07/16
 * Time: 7:40 AM
 */

namespace MilesAsylum\Schnoop\Tests\Schnoop\Schema\MySQL\DataType;

use MilesAsylum\Schnoop\Schema\MySQL\DataType\DataTypeInterface;
use MilesAsylum\Schnoop\Schema\MySQL\DataType\YearType;

class YearTypeTest extends \PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $yearType = new YearType();
        $yearStr = '2016';

        $this->assertSame(DataTypeInterface::TYPE_YEAR, $yearType->getType());
        $this->assertTrue($yearType->doesAllowDefault());
        $this->assertSame((int)$yearStr, $yearType->cast($yearStr));
    }
}
