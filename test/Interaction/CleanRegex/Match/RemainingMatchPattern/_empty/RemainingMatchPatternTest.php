<?php
namespace Test\Interaction\TRegx\CleanRegex\Match\RemainingMatchPattern\_empty;

use PHPUnit\Framework\TestCase;
use Test\Fakes\CleanRegex\Internal\Match\Base\ThrowApiBase;
use Test\Fakes\CleanRegex\Internal\Match\CallbackPredicate;
use Test\Fakes\CleanRegex\Internal\Match\MatchAll\ThrowFactory;
use Test\Utils\AssertsOptional;
use Test\Utils\AssertsSameMatches;
use Test\Utils\Definitions;
use Test\Utils\DetailFunctions;
use Test\Utils\Functions;
use TRegx\CleanRegex\Exception\SubjectNotMatchedException;
use TRegx\CleanRegex\Internal\Match\Base\ApiBase;
use TRegx\CleanRegex\Internal\Match\Base\DetailPredicateBaseDecorator;
use TRegx\CleanRegex\Internal\Match\UserData;
use TRegx\CleanRegex\Internal\StringSubject;
use TRegx\CleanRegex\Match\Details\Detail;
use TRegx\CleanRegex\Match\RemainingMatchPattern;

/**
 * @covers \TRegx\CleanRegex\Match\RemainingMatchPattern
 */
class RemainingMatchPatternTest extends TestCase
{
    use AssertsSameMatches, AssertsOptional;

    /**
     * @test
     */
    public function shouldGet_All()
    {
        // given
        $matchPattern = $this->matchPattern(DetailFunctions::notEquals('b'));

        // when
        $all = $matchPattern->all();

        // then
        $this->assertSame(['', 'a', '', 'c'], $all);
    }

    /**
     * @test
     */
    public function shouldOnly_2()
    {
        // given
        $matchPattern = $this->matchPattern(DetailFunctions::notEquals('b'));

        // when
        $only = $matchPattern->only(2);

        // then
        $this->assertSame(['', 'a'], $only);
    }

    /**
     * @test
     */
    public function shouldOnly_1()
    {
        // given
        $matchPattern = $this->matchPattern(DetailFunctions::notEquals('b'));

        // when
        $only = $matchPattern->only(1);

        // then
        $this->assertSame([''], $only);
    }

    /**
     * @test
     */
    public function shouldCount()
    {
        // given
        $matchPattern = $this->matchPattern(DetailFunctions::notEquals('b'));

        // when
        $count = $matchPattern->count();

        // then
        $this->assertSame(4, $count);
    }

    /**
     * @test
     */
    public function shouldCount_all()
    {
        // given
        $matchPattern = $this->matchPattern(Functions::constant(true));

        // when
        $count = $matchPattern->count();

        // then
        $this->assertSame(5, $count);
    }

    /**
     * @test
     */
    public function shouldGet_First()
    {
        // given
        $matchPattern = $this->matchPattern(DetailFunctions::notEquals('b'));

        // when
        $first = $matchPattern->first();

        // then
        $this->assertSame('', $first);
    }

    /**
     * @test
     */
    public function shouldGet_First_notFirst()
    {
        // given
        $matchPattern = $this->matchPattern(DetailFunctions::indexNotEquals(0));

        // when
        $first = $matchPattern->first();

        // then
        $this->assertSame('a', $first);
    }

    /**
     * @test
     */
    public function shouldNotGet_First_matchedButFiltered()
    {
        // given
        $matchPattern = $this->matchPattern(Functions::constant(false));

        // then
        $this->expectException(SubjectNotMatchedException::class);
        $this->expectExceptionMessage('Expected to get the first match, but subject was not matched');

        // when
        $matchPattern->first();
    }

    /**
     * @test
     */
    public function shouldGet_findFirst()
    {
        // given
        $matchPattern = $this->matchPattern(DetailFunctions::notEquals('b'));

        // when
        $findFirst = $matchPattern->findFirst(function (Detail $detail) {
            return "value: $detail";
        });

        // then
        $this->assertOptionalHas('value: ', $findFirst);
    }

    /**
     * @test
     */
    public function shouldGet_findFirst_notFirst()
    {
        // given
        $matchPattern = $this->matchPattern(DetailFunctions::indexNotEquals(0));

        // when
        $findFirst = $matchPattern->findFirst(function (Detail $detail) {
            return "value: $detail";
        });

        // then
        $this->assertOptionalHas('value: a', $findFirst);
    }

    /**
     * @test
     */
    public function shouldNotGet_findFirst_matchedButFiltered()
    {
        // given
        $matchPattern = $this->matchPattern(Functions::constant(false));

        // when
        $findFirst = $matchPattern->findFirst(Functions::fail());

        // then
        $this->assertOptionalEmpty($findFirst);
    }

    /**
     * @test
     */
    public function shouldMatch_all()
    {
        // given
        $matchPattern = $this->matchPattern(Functions::constant(true));

        // when
        $matches = $matchPattern->test();
        $fails = $matchPattern->fails();

        // then
        $this->assertTrue($matches);
        $this->assertFalse($fails);
    }

    /**
     * @test
     */
    public function shouldMatch_some()
    {
        // given
        $matchPattern = $this->matchPattern(DetailFunctions::notEquals('b'));

        // when
        $matches = $matchPattern->test();
        $fails = $matchPattern->fails();

        // then
        $this->assertTrue($matches);
        $this->assertFalse($fails);
    }

    /**
     * @test
     */
    public function shouldNotMatch_matchedButFiltered()
    {
        // given
        $matchPattern = $this->matchPattern(Functions::constant(false));

        // when
        $matches = $matchPattern->test();
        $fails = $matchPattern->fails();

        // then
        $this->assertFalse($matches);
        $this->assertTrue($fails);
    }

    /**
     * @test
     */
    public function shouldFlatMap()
    {
        // given
        $matchPattern = $this->matchPattern(DetailFunctions::indexNotEquals(0));

        // when
        $flatMap = $matchPattern->flatMap(function (Detail $detail) {
            return str_split(str_repeat($detail, 2));
        });

        // then
        $this->assertSame(['a', 'a', 'b', 'b', '', 'c', 'c'], $flatMap);
    }

    /**
     * @test
     */
    public function shouldGet_Offsets_all()
    {
        // given
        $matchPattern = $this->matchPattern(DetailFunctions::notEquals('b'));

        // when
        $offsets = $matchPattern->offsets()->all();

        // then
        $this->assertSame([1, 4, 12, 15], $offsets);
    }

    /**
     * @test
     */
    public function shouldGet_Offsets_first()
    {
        // given
        $matchPattern = $this->matchPattern(DetailFunctions::notEquals('b'));

        // when
        $offset = $matchPattern->offsets()->first();

        // then
        $this->assertSame(1, $offset);
    }

    /**
     * @test
     */
    public function shouldMap()
    {
        // given
        $matchPattern = $this->matchPattern(DetailFunctions::notEquals('b'));
        $mapper = function (Detail $detail) {
            return lcfirst($detail) . ucfirst($detail);
        };

        // when
        $mapped = $matchPattern->map($mapper);

        // then
        $this->assertSame(['', 'aA', '', 'cC'], $mapped);
    }

    /**
     * @test
     */
    public function shouldFindFirst()
    {
        // given
        $matchPattern = $this->matchPattern(DetailFunctions::notEquals('b'));

        // when
        $first = $matchPattern
            ->findFirst(function (Detail $detail) {
                return "for first: $detail";
            })
            ->orReturn('');

        // then
        $this->assertSame('for first: ', $first);
    }

    /**
     * @test
     */
    public function shouldChain_remaining()
    {
        // given
        $subject = '...you forgot one very important thing mate.';
        $pattern = new RemainingMatchPattern(
            new DetailPredicateBaseDecorator(
                new ApiBase(Definitions::pcre('/[a-z]+/'), new StringSubject($subject), new UserData()),
                new CallbackPredicate(DetailFunctions::notEquals('forgot'))),
            new ThrowApiBase(),
            new ThrowFactory());

        // when
        $filtered = $pattern
            ->remaining(DetailFunctions::notEquals('very'))
            ->remaining(DetailFunctions::notEquals('mate'))
            ->all();

        // then
        $this->assertSame(['you', 'one', 'important', 'thing'], $filtered);
    }

    /**
     * @test
     */
    public function shouldForEach()
    {
        // given
        $matchPattern = $this->matchPattern(DetailFunctions::notEquals('b'));

        // when
        $matchPattern->forEach(DetailFunctions::collecting($matches));

        // then
        $this->assertSame(['', 'a', 3 => '', 'c'], $matches);
    }

    private function matchPattern(callable $predicate): RemainingMatchPattern
    {
        return new RemainingMatchPattern(
            new DetailPredicateBaseDecorator(
                new ApiBase(Definitions::pattern('(?<=\()[a-z]?(?=\))'), new StringSubject('() (a) (b) () (c)'), new UserData()),
                new CallbackPredicate($predicate)),
            new ThrowApiBase(),
            new ThrowFactory());
    }
}
