<?php
namespace Test\Feature\CleanRegex\Replaced\callback\Details\texts;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Match\Details\Detail;

class Test extends TestCase
{
    /**
     * @test
     */
    public function shouldGetGroupTexts()
    {
        // given
        pattern('(Foo)(Bar)(Cat)')
            ->replaced('FooBarCat')
            ->callback(function (Detail $detail) {
                // when
                $groups = $detail->groups()->texts();
                $groupsNamed = $detail->namedGroups()->texts();

                // then
                $this->assertSame(['Foo', 'Bar', 'Cat'], $groups);
                $this->assertSame([], $groupsNamed);

                // cleanup
                return '';
            });
    }

    /**
     * @test
     */
    public function shouldGetUnmatchedGroupTexts()
    {
        // given
        pattern('(Foo)(Bar)?(Cat)')
            ->replaced('FooCat')
            ->callback(function (Detail $detail) {
                // when
                $groups = $detail->groups()->texts();
                $namedGroups = $detail->namedGroups()->texts();

                // then
                $this->assertSame(['Foo', null, 'Cat'], $groups);
                $this->assertSame([], $namedGroups);

                // cleanup
                return '';
            });
    }

    /**
     * @test
     */
    public function shouldGetUnmatchedGroupTextsLast()
    {
        // given
        pattern('(Foo)(Bar)(Cat)?')
            ->replaced('FooBar')
            ->callback(function (Detail $detail) {
                // when
                $groups = $detail->groups()->texts();
                $namedGroups = $detail->namedGroups()->texts();

                // then
                $this->assertSame(['Foo', 'Bar', null], $groups);
                $this->assertSame([], $namedGroups);

                // cleanup
                return '';
            });
    }

    /**
     * @test
     */
    public function shouldGetUnmatchedGroupTextsLastEmpty()
    {
        // given
        pattern('(Foo)(Bar)()')
            ->replaced('FooBar')
            ->callback(function (Detail $detail) {
                // when
                $groups = $detail->groups()->texts();
                $namedGroups = $detail->namedGroups()->texts();

                // then
                $this->assertSame(['Foo', 'Bar', ''], $groups);
                $this->assertSame([], $namedGroups);

                // cleanup
                return '';
            });
    }

    /**
     * @test
     */
    public function shouldGetUnmatchedGroupTextsLastMissingEmpty()
    {
        // given
        pattern('(Foo)(Bar)(Cat)?()')
            ->replaced('FooBar')
            ->callback(function (Detail $detail) {
                // when
                $groups = $detail->groups()->texts();
                // then
                $this->assertSame(['Foo', 'Bar', null, ''], $groups);

                // cleanup
                return '';
            });
    }

    /**
     * @test
     */
    public function shouldGetEmptyGroupTexts()
    {
        // given
        pattern('(Foo)()(Cat)')
            ->replaced('FooCat')
            ->callback(function (Detail $detail) {
                // when
                $groups = $detail->groups()->texts();
                $namedGroups = $detail->namedGroups()->texts();

                // then
                $this->assertSame(['Foo', '', 'Cat'], $groups);
                $this->assertSame([], $namedGroups);

                // cleanup
                return '';
            });
    }

    /**
     * @test
     */
    public function shouldGetGroupsNames()
    {
        // given
        pattern('(zero) (?<existing>first) and (?<two_existing>second)')
            ->replaced('zero first and second')
            ->callback(function (Detail $detail) {
                // when
                $namesIndexed = $detail->groups()->names();
                $namesNamed = $detail->namedGroups()->names();

                // then
                $this->assertSame([null, 'existing', 'two_existing'], $namesIndexed);
                $this->assertSame(['existing', 'two_existing'], $namesNamed);

                // clean up
                return '';
            });
    }

    /**
     * @test
     */
    public function shouldGetGroupsTextsNamed()
    {
        // given
        pattern('(zero) (?<existing>first) and (?<two_existing>second)')
            ->replaced('zero first and second')
            ->callback(function (Detail $detail) {
                // when
                $namesIndexed = $detail->groups()->texts();
                $namesNamed = $detail->namedGroups()->texts();

                // then
                $this->assertSame(['zero', 'first', 'second'], $namesIndexed);
                $this->assertSame(['existing' => 'first', 'two_existing' => 'second'], $namesNamed);

                // clean up
                return '';
            });
    }

    /**
     * @test
     */
    public function shouldGetGroupsTextsNamedOptional()
    {
        // given
        pattern('(zero) (?<existing>first)? and (?<two_existing>second)')
            ->replaced('zero  and second')
            ->callback(function (Detail $detail) {
                // when
                $namesIndexed = $detail->groups()->texts();
                $namesNamed = $detail->namedGroups()->texts();

                // then
                $this->assertSame(['zero', null, 'second'], $namesIndexed);
                $this->assertSame(['existing' => null, 'two_existing' => 'second'], $namesNamed);

                // clean up
                return '';
            });
    }
}
