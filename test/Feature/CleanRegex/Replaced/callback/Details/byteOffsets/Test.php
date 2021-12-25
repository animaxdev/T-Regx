<?php
namespace Test\Feature\CleanRegex\Replaced\callback\Details\byteOffsets;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Match\Details\Detail;

class Test extends TestCase
{
    /**
     * @test
     */
    public function shouldGetGroupByteOffsets()
    {
        // given
        pattern('(Foo)(Bar)(Cat)')
            ->replaced('FooBarCat')
            ->callback(function (Detail $detail) {
                // when
                $byteOffsets = $detail->groups()->byteOffsets();
                $byteOffsetsNamed = $detail->namedGroups()->byteOffsets();

                // then
                $this->assertSame([0, 3, 6], $byteOffsets);
                $this->assertSame([], $byteOffsetsNamed);

                // cleanup
                return '';
            });
    }

    /**
     * @test
     */
    public function shouldGetUnmatchedGroupByteOffsets()
    {
        // given
        pattern('(Foo)(Bar)?(Cat)')
            ->replaced('FooCat')
            ->callback(function (Detail $detail) {
                // when
                $byteOffsets = $detail->groups()->byteOffsets();
                $byteOffsetsNamed = $detail->namedGroups()->byteOffsets();

                // then
                $this->assertSame([0, null, 3], $byteOffsets);
                $this->assertSame([], $byteOffsetsNamed);

                // cleanup
                return '';
            });
    }

    /**
     * @test
     */
    public function shouldGetUnmatchedGroupByteOffsetsSecondMatch()
    {
        // given
        pattern('(Foo)(Bar)?(Cat)')
            ->replaced('FooCat FooCat')
            ->callback(function (Detail $detail) {
                // ignore the first match
                if ($detail->index() === 0) {
                    return '';
                }
                // when
                $byteOffsets = $detail->groups()->byteOffsets();
                $byteOffsetsNamed = $detail->namedGroups()->byteOffsets();

                // then
                $this->assertSame([7, null, 10], $byteOffsets);
                $this->assertSame([], $byteOffsetsNamed);

                // cleanup
                return '';
            });
    }

    /**
     * @test
     */
    public function shouldGetEmptyGroupByteOffsets()
    {
        // given
        pattern('(Foo)()(Cat)')
            ->replaced('FooCat')
            ->callback(function (Detail $detail) {
                // when
                $byteOffsets = $detail->groups()->byteOffsets();
                $byteOffsetsNamed = $detail->namedGroups()->byteOffsets();

                // then
                $this->assertSame([0, 3, 3], $byteOffsets);
                $this->assertSame([], $byteOffsetsNamed);

                // cleanup
                return '';
            });
    }

    /**
     * @test
     */
    public function shouldGetGroupsOffsetsNamed()
    {
        // given
        pattern('(zero) (?<existing>first) and (?<two_existing>second)')
            ->replaced('zero first and second')
            ->callback(function (Detail $detail) {
                // when
                $namesIndexed = $detail->groups()->byteOffsets();
                $namesNamed = $detail->namedGroups()->byteOffsets();

                // then
                $this->assertSame([0, 5, 15], $namesIndexed);
                $this->assertSame(['existing' => 5, 'two_existing' => 15], $namesNamed);

                // clean up
                return '';
            });
    }

    /**
     * @test
     */
    public function shouldGetGroupsOffsetsNamedOptional()
    {
        // given
        pattern('(zero) (?<existing>first)? and (?<two_existing>second)')
            ->replaced('zero  and second')
            ->callback(function (Detail $detail) {
                // when
                $namesIndexed = $detail->groups()->byteOffsets();
                $namesNamed = $detail->namedGroups()->byteOffsets();

                // then
                $this->assertSame([0, null, 10], $namesIndexed);
                $this->assertSame(['existing' => null, 'two_existing' => 10], $namesNamed);

                // clean up
                return '';
            });
    }

    /**
     * @test
     */
    public function shouldGetGroupsOffsetsNamedOptionalLast()
    {
        // given
        pattern('(zero) (?<existing>first) and (?<two_existing>second)?')
            ->replaced('zero first and ')
            ->callback(function (Detail $detail) {
                // when
                $namesIndexed = $detail->groups()->byteOffsets();
                $namesNamed = $detail->namedGroups()->byteOffsets();

                // then
                $this->assertSame([0, 5, null], $namesIndexed);
                $this->assertSame(['existing' => 5, 'two_existing' => null], $namesNamed);

                // clean up
                return '';
            });
    }
}
