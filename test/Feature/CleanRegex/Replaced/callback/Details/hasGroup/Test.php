<?php
namespace Test\Feature\TRegx\CleanRegex\Replaced\callback\Details\hasGroup;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Match\Details\Detail;

class Test extends TestCase
{
    /**
     * @test
     */
    public function shouldHaveWholeGroup()
    {
        // given
        pattern('Foo')->replaced('Foo')->callback(function (Detail $detail) {
            // when + then
            $this->assertTrue($detail->hasGroup(0));

            // after
            return '';
        });
    }

    /**
     * @test
     */
    public function shouldHaveFirstGroup()
    {
        // given
        pattern('(Foo)')->replaced('Foo')->callback(function (Detail $detail) {
            // when + then
            $this->assertTrue($detail->hasGroup(1));

            // after
            return '';
        });
    }

    /**
     * @test
     */
    public function shouldNotHaveFirstGroup()
    {
        // given
        pattern('Foo')->replaced('Foo')->callback(function (Detail $detail) {
            // when + then
            $this->assertFalse($detail->hasGroup(1));

            // after
            return '';
        });
    }

    /**
     * @test
     */
    public function shouldHaveNamedGroup()
    {
        // given
        pattern('(?<group>Foo)')->replaced('Foo')->callback(function (Detail $detail) {
            // when + then
            $this->assertTrue($detail->hasGroup('group'));

            // after
            return '';
        });
    }

    /**
     * @test
     */
    public function shouldNotHaveNamedGroup()
    {
        // given
        pattern('Foo')->replaced('Foo')->callback(function (Detail $detail) {
            // when + then
            $this->assertFalse($detail->hasGroup('group'));

            // after
            return '';
        });
    }

    /**
     * @test
     */
    public function shouldThrowForInvalidGroupName()
    {
        // then
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Group name must be an alphanumeric string, not starting with a digit, but '2group' given");

        // given
        pattern('Foo')->replaced('Foo')->callback(function (Detail $detail) {
            $detail->hasGroup('2group');
        });
    }

    /**
     * @test
     */
    public function shouldThrowForInvalidGroupIndex()
    {
        // then
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Group index must be a non-negative integer, but -1 given');

        // given
        pattern('Foo')->replaced('Foo')->callback(function (Detail $detail) {
            $detail->hasGroup(-1);
        });
    }

    /**
     * @test
     */
    public function shouldHaveLastUnmatchedGroup()
    {
        // given
        pattern('(Foo)?')->replaced('')->callback(function (Detail $detail) {
            // when + then
            $this->assertTrue($detail->hasGroup(1));

            // after
            return '';
        });
    }
}
