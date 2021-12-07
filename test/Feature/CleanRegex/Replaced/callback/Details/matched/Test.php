<?php
namespace Test\Feature\CleanRegex\Replaced\callback\Details\matched;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Exception\NonexistentGroupException;
use TRegx\CleanRegex\Match\Details\Detail;

class Test extends TestCase
{
    /**
     * @test
     */
    public function shouldGroupNotBeMatched()
    {
        // given
        pattern('(Foo)?(Bar)')->replaced('Bar')->callback(function (Detail $detail) {
            // when + then
            $this->assertFalse($detail->matched(1));

            // after
            return '';
        });
    }

    /**
     * @test
     */
    public function shouldEmptyGroupBeMatched()
    {
        // given
        pattern('()')->replaced('')->callback(function (Detail $detail) {
            // when + then
            $this->assertTrue($detail->matched(1));

            // after
            return '';
        });
    }

    /**
     * @test
     */
    public function shouldGroupBeMatched()
    {
        // given
        pattern('(Foo)')->replaced('Foo')->callback(function (Detail $detail) {
            // when + then
            $this->assertTrue($detail->matched(1));

            // after
            return '';
        });
    }

    /**
     * @test
     */
    public function shouldThrowForNonexistentGroup()
    {
        // then
        $this->expectException(NonexistentGroupException::class);
        $this->expectExceptionMessage("Nonexistent group: 'missing'");

        // given
        pattern('Foo')->replaced('Foo')->callback(function (Detail $detail) {
            // when
            $detail->matched('missing');
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
            // when
            $detail->matched('2group');
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
            // when
            $detail->matched(-1);
        });
    }

    /**
     * @test
     */
    public function shouldLastGroupBeMatched()
    {
        // given
        pattern('(Foo)(Bar)')->replaced('FooBar')->callback(function (Detail $detail) {
            // when + then
            $this->assertTrue($detail->matched(2));

            // after
            return '';
        });
    }

    /**
     * @test
     */
    public function shouldLastGroupNotBeMatched()
    {
        // given
        pattern('(Foo)(Bar)?')->replaced('Foo')->callback(function (Detail $detail) {
            // when + then
            $this->assertFalse($detail->matched(2));

            // after
            return '';
        });
    }
}
