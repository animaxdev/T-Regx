<?php
namespace Test\Feature\CleanRegex\Replaced\exactly;

use PHPUnit\Framework\TestCase;
use Test\Utils\CatastrophicBacktracking;
use TRegx\CleanRegex\Exception\ReplacementExpectationFailedException;
use TRegx\SafeRegex\Exception\CatastrophicBacktrackingException;

/**
 * @coversNothing
 */
class Test extends TestCase
{
    use CatastrophicBacktracking;

    /**
     * @test
     */
    public function testExactlyFirst()
    {
        // when
        $result = pattern('Foo')->replaced('Hi:Foo')->exactly()->first()->with('Dead');

        // then
        $this->assertSame('Hi:Dead', $result);
    }

    /**
     * @test
     */
    public function testExactlyFirst_Superfluous()
    {
        // then
        $this->expectException(ReplacementExpectationFailedException::class);
        $this->expectExceptionMessage('Expected to perform exactly 1 replacement(s), but at least 2 replacement(s) would have been performed');

        // when
        pattern('Foo')->replaced('Foo, Foo')->exactly()->first()->with('Dead');
    }

    /**
     * @test
     */
    public function testExactlyFirst_Insufficient()
    {
        // then
        $this->expectException(ReplacementExpectationFailedException::class);
        $this->expectExceptionMessage('Expected to perform exactly 1 replacement(s), but 0 replacement(s) were actually performed');

        // when
        pattern('Foo')->replaced('Bar')->exactly()->first()->with('Dead');
    }

    /**
     * @test
     */
    public function shouldThrowCatastrophicBacktracking_WhileCheckingLast()
    {
        // given
        [$pattern, $subject] = $this->catastrophicBacktracking();

        // then
        $this->expectException(CatastrophicBacktrackingException::class);

        // when
        pattern($pattern)->replaced($subject)->exactly()->only(2)->with('Bar');
    }
}
