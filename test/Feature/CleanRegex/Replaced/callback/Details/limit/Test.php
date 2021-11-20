<?php
namespace Test\Feature\CleanRegex\Replaced\callback\Details\limit;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Match\Details\Detail;

class Test extends TestCase
{
    /**
     * @test
     */
    public function shouldBeLimited_replace_first()
    {
        // given
        pattern('\d+')
            ->replaced('111-222-333')
            ->first()
            ->callback(function (Detail $detail) {
                // when
                $this->assertSame(1, $detail->limit());

                // clean up
                return '';
            });
    }

    /**
     * @test
     */
    public function shouldBeLimited_replace_all()
    {
        // given
        pattern('\d+')
            ->replaced('111-222-333')
            ->all()
            ->callback(function (Detail $detail) {
                // then
                $this->assertSame(-1, $detail->limit());

                // clean up
                return '';
            });
    }

    /**
     * @test
     */
    public function shouldBeLimited_replace_only_3()
    {
        // given
        pattern('\d+')
            ->replaced('111-222-333')
            ->only(3)
            ->callback(function (Detail $detail) {
                // when
                $this->assertSame(3, $detail->limit());

                // clean up
                return '';
            });
    }
}
