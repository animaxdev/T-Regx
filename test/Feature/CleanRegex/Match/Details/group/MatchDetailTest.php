<?php
namespace Test\Feature\TRegx\CleanRegex\Match\Details\group;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Test\Utils\Functions;
use TRegx\CleanRegex\Exception\GroupNotMatchedException;
use TRegx\CleanRegex\Exception\NonexistentGroupException;
use TRegx\CleanRegex\Match\Details\Detail;
use TRegx\CleanRegex\Match\Details\Group\Group;
use function pattern;

class MatchDetailTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetGroup()
    {
        // given
        pattern('Hello (?<one>there)')
            ->match('Hello there, General Kenobi')
            ->first(function (Detail $detail) {
                // then
                $this->assertSame('there', "" . $detail->group('one'));
                $this->assertSame('there', $detail->group('one')->text());
                $this->assertSame(6, $detail->group('one')->offset());
                $this->assertTrue($detail->group('one')->matched());

                $this->assertTrue($detail->hasGroup('one'));
                $this->assertFalse($detail->hasGroup('two'));
            });
    }

    /**
     * @test
     */
    public function shouldGet_groupTextLength()
    {
        // given
        pattern('(\p{L}+)', 'u')
            ->match('Łomża')
            ->first(function (Detail $detail) {
                // then
                $this->assertSame('Łomża', $detail->group(1)->text());
                $this->assertSame(5, $detail->group(1)->textLength());
                $this->assertSame(7, $detail->group(1)->textByteLength());
            });
    }

    /**
     * @test
     */
    public function shouldGetGroup_all_matched()
    {
        // given
        pattern('Hello (?<one>there|here)?')
            ->match('Hello there, General Kenobi, maybe Hello and Hello here')
            ->first(function (Detail $detail) {
                // when
                $all = $detail->all();
                $groupAll = $detail->group('one')->all();

                // then
                $this->assertSame(['Hello there', 'Hello ', 'Hello here'], $all);
                $this->assertSame(['there', null, 'here'], $groupAll);
            });
    }

    /**
     * @test
     */
    public function shouldGetGroup_all_unmatched()
    {
        // given
        pattern('Hello (?<one>there|here)?')
            ->match('Hello , General Kenobi, maybe Hello there and Hello here')
            ->first(function (Detail $detail) {
                // when
                $groupAll = $detail->group('one')->all();

                // then
                $this->assertSame([null, 'there', 'here'], $groupAll);
            });
    }

    /**
     * @test
     */
    public function shouldGetGroup_empty_string()
    {
        // when
        pattern('Hello (?<one>there|here|)')
            ->match('Hello there, General Kenobi, maybe Hello and Hello here')
            ->first(function (Detail $detail) {
                // when
                $all = $detail->all();
                $groupAll = $detail->group('one')->all();

                // then
                $this->assertSame(['Hello there', 'Hello ', 'Hello here'], $all);
                $this->assertSame(['there', '', 'here'], $groupAll);
            });
    }

    /**
     * @test
     */
    public function shouldGetSubject()
    {
        // when
        pattern('(?<matched>Foo)(?<unmatched>Bar)?')
            ->match('Hello:Foo')
            ->first(function (Detail $detail) {
                // given
                $matched = $detail->group('matched');
                $unmatched = $detail->group('unmatched');

                // when
                $matchedSubject = $matched->subject();
                $unmatchedSubject = $unmatched->subject();

                // then
                $this->assertSame('Hello:Foo', $matchedSubject);
                $this->assertSame('Hello:Foo', $unmatchedSubject);
            });
    }

    /**
     * @test
     * @dataProvider shouldGroup_notMatch_dataProvider
     * @param string $pattern
     * @param string $subject
     */
    public function shouldGroup_notMatch(string $pattern, string $subject)
    {
        // given
        pattern($pattern)->match($subject)->first(function (Detail $detail) {
            $group = $detail->group('one');

            // when
            $matches = $group->matched();

            // then
            $this->assertFalse($matches);
        });
    }

    public function shouldGroup_notMatch_dataProvider(): array
    {
        return [
            ['Hello (?<one>there)?', 'Hello XX, General Kenobi'],
            ['Hello (?<one>there)?(?<two>XX)', 'Hello XX, General Kenobi'],
        ];
    }

    /**
     * @test
     */
    public function shouldThrow_onMissingGroup()
    {
        // then
        $this->expectException(NonexistentGroupException::class);
        $this->expectExceptionMessage("Nonexistent group: 'two'");

        // when
        pattern('(?<one>hello)')
            ->match('hello')
            ->first(function (Detail $detail) {
                $detail->group('two');
            });
    }

    /**
     * @test
     */
    public function shouldValidateGroupName()
    {
        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Group index must be an integer or a string, but boolean (true) given');

        // given
        pattern('(?<one>first) and (?<two>second)')
            ->match('first and second')
            ->first(function (Detail $detail) {
                // when
                $detail->group(true);
            });
    }

    /**
     * @test
     */
    public function shouldThrowGroupNotMatchedException()
    {
        // then
        $this->expectException(GroupNotMatchedException::class);
        $this->expectExceptionMessage("Expected to get group 'group', but it was not matched");

        // when
        pattern('(?<group>Foo)?')
            ->match('Bar')
            ->first(function (Detail $detail) {
                $detail->group('group')->orThrow();
            });
    }

    /**
     * @test
     */
    public function shouldMapGroupOptional()
    {
        // when
        $group = pattern('foo:(\w+)')->match('foo:bar')
            ->first(function (Detail $detail) {
                return $detail->group(1)
                    ->map(function (Group $group) {
                        return \strToUpper($group->text());
                    })
                    ->orThrow();
            });

        // then
        $this->assertSame('BAR', $group);
    }

    /**
     * @test
     */
    public function shouldMapGroupOptionalEmpty()
    {
        // then
        $this->expectException(GroupNotMatchedException::class);
        $this->expectExceptionMessage('Expected to get group #1, but it was not matched');

        // when
        pattern('foo:(\w+)?')->match('foo:')
            ->first(function (Detail $detail) {
                return $detail->group(1)->map(Functions::fail())->orThrow();
            });
    }

    /**
     * @test
     */
    public function shouldMapGroupOptionalUsingDuplicateName()
    {
        // when
        $group = pattern('foo:(?<name>\w+)')->match('foo:bar')
            ->first(function (Detail $detail) {
                return $detail->usingDuplicateName()->group('name')
                    ->map(function (Group $group) {
                        return \strToUpper($group->text());
                    })
                    ->orThrow();
            });

        // then
        $this->assertSame('BAR', $group);
    }
}
