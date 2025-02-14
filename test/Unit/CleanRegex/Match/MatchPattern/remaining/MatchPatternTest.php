<?php
namespace Test\Unit\TRegx\CleanRegex\Match\MatchPattern\remaining;

use PHPUnit\Framework\TestCase;
use Test\Utils\Definitions;
use Test\Utils\DetailFunctions;
use Test\Utils\Functions;
use TRegx\CleanRegex\Exception\InvalidReturnValueException;
use TRegx\CleanRegex\Internal\StringSubject;
use TRegx\CleanRegex\Match\MatchPattern;

/**
 * @covers \TRegx\CleanRegex\Match\MatchPattern::remaining
 */
class MatchPatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetAll()
    {
        // given
        $pattern = new MatchPattern(Definitions::pattern('\w+'), new StringSubject('Nice matching pattern'));

        // when
        $remaining = $pattern->remaining(DetailFunctions::notEquals('Nice'))->all();

        // then
        $this->assertSame(['matching', 'pattern'], $remaining);
    }

    /**
     * @test
     */
    public function shouldThrow_onInvalidReturnType()
    {
        // given
        $pattern = new MatchPattern(Definitions::pattern('Foo'), new StringSubject('Foo'));

        // then
        $this->expectException(InvalidReturnValueException::class);
        $this->expectExceptionMessage('Invalid remaining() callback return type. Expected bool, but integer (4) given');

        // when
        $pattern->remaining(Functions::constant(4))->all();
    }
}
