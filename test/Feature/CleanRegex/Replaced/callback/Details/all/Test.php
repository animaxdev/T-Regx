<?php
namespace Test\Feature\CleanRegex\Replaced\callback\Details\all;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Match\Details\Detail;

class Test extends TestCase
{
    /**
     * @test
     */
    public function shouldGetAll()
    {
        // given
        pattern('\d+')
            ->replaced('123, 345, 678')
            ->callback(function (Detail $detail) {
                $this->assertSame(['123', '345', '678'], $detail->all());

                // cleanup
                return '';
            });
    }

    /**
     * @test
     */
    public function shouldGetAll_First()
    {
        // given
        pattern('\d+')
            ->replaced('123, 345, 678')
            ->first()
            ->callback(function (Detail $detail) {
                $this->assertSame('123', $detail->text());
                $this->assertSame(['123', '345', '678'], $detail->all());

                // cleanup
                return '';
            });
    }
}
