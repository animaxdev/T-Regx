<?php
namespace Test\Feature\CleanRegex\Replaced\callback\Details\isInt;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Replaced\Callback\Detail\ReplaceDetail;

class Test extends TestCase
{
    /**
     * @test
     */
    public function shouldBeInteger()
    {
        // given
        pattern('1094')
            ->replaced('1094')
            ->first()
            ->callback(function (ReplaceDetail $detail) {
                $this->assertTrue($detail->isInt());

                // cleanup
                return '';
            });
    }

    /**
     * @test
     */
    public function shouldBeIntegerBase11()
    {
        // given
        pattern('9a')
            ->replaced('9a')
            ->first()
            ->callback(function (ReplaceDetail $detail) {
                $this->assertTrue($detail->isInt(11));

                // cleanup
                return '';
            });
    }

    /**
     * @test
     */
    public function shouldNotBeIntegerBase10()
    {
        // given
        pattern('a0')
            ->replaced('a0')
            ->first()
            ->callback(function (ReplaceDetail $detail) {
                $this->assertFalse($detail->isInt());

                // cleanup
                return '';
            });
    }

    /**
     * @test
     */
    public function shouldNotBeIntegerBase9()
    {
        // given
        pattern('9')
            ->replaced('9')
            ->first()
            ->callback(function (ReplaceDetail $detail) {
                $this->assertFalse($detail->isInt(9));

                // cleanup
                return '';
            });
    }
}
