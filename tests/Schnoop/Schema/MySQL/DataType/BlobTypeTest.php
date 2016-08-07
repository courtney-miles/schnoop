<?php
/**
 * Created by PhpStorm.
 * User: courtney
 * Date: 23/06/16
 * Time: 7:35 AM
 */

namespace MilesAsylum\Schnoop\Tests\Schnoop\Schema\MySQL\DataType;

use MilesAsylum\Schnoop\PHPUnit\Framework\SchnoopTestCase;
use MilesAsylum\Schnoop\Schema\MySQL\DataType\BlobType;
use MilesAsylum\Schnoop\Schema\MySQL\DataType\DataTypeInterface;

class BlobTypeTest extends SchnoopTestCase
{
    /**
     * @var BlobType
     */
    protected $blobType;

    public function setUp()
    {
        parent::setUp();

        $this->blobType = new BlobType();
    }

    public function testConstruct()
    {
        $this->binaryTypeAsserts(
            DataTypeInterface::TYPE_BLOB,
            pow(2, 16)-1,
            false,
            'BLOB',
            $this->blobType
        );
    }

    public function testCast()
    {
        $this->assertSame('123', $this->blobType->cast(123));
    }
}
