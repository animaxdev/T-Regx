<?php
namespace Test\Feature\TRegx\CleanRegex\Replaced\callback\Details\groupNames;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Match\Details\Detail;

class Test extends TestCase
{
    /**
     * @test
     */
    public function shouldGetEmptyGroupNames()
    {
        pattern('Foo')
            ->replaced('Foo')
            ->callback(function (Detail $detail) {
                // when + then
                $this->assertSame([], $detail->groupNames());

                // after
                return '';
            });
    }

    /**
     * @test
     */
    public function shouldGetGroupNames()
    {
        pattern('(?<name>Foo)(?<second>Bar)')
            ->replaced('FooBar')
            ->callback(function (Detail $detail) {
                // when + then
                $this->assertSame(['name', 'second'], $detail->groupNames());

                // after
                return '';
            });
    }

    /**
     * @test
     */
    public function shouldGetGroupNamesLastUnmatched()
    {
        pattern('(?<name>Foo)(?<second>Bar)?')
            ->replaced('Foo')
            ->callback(function (Detail $detail) {
                // when + then
                $this->assertSame(['name', 'second'], $detail->groupNames());

                // after
                return '';
            });
    }

    /**
     * @test
     */
    public function shouldGetGroupNamesLastMatchedEmpty()
    {
        pattern('(?<name>Foo)(?<second>)Bar')
            ->replaced('FooBar')
            ->callback(function (Detail $detail) {
                // when + then
                $this->assertSame(['name', 'second'], $detail->groupNames());

                // after
                return '';
            });
    }

    /**
     * @test
     */
    public function shouldGetUnnamedGroups()
    {
        pattern('(One)(?<two>Two)(Three)(?<four>Four)')
            ->replaced('OneTwoThreeFour')
            ->callback(function (Detail $detail) {
                // when + then
                $this->assertSame([null, 'two', null, 'four'], $detail->groupNames());

                // after
                return '';
            });
    }
}
