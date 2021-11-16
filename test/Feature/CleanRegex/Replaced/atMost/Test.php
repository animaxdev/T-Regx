<?php
namespace Test\Feature\CleanRegex\Replaced\atMost;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Exception\ReplacementExpectationFailedException;

/**
 * @coversNothing
 */
class Test extends TestCase
{
    /**
     * @test
     */
    public function shouldOnly2Replace_2occurrences()
    {
        // when
        $result = pattern('Foo')->replaced('Foo,Foo')->atMost()->only(2)->with('Bar');

        // then
        $this->assertSame('Bar,Bar', $result);
    }

    /**
     * @test
     */
    public function shouldOnly3Replace_2occurrences()
    {
        // when
        $result = pattern('Foo')->replaced('Foo,Foo')->atMost()->only(3)->with('Bar');

        // then
        $this->assertSame('Bar,Bar', $result);
    }

    /**
     * @test
     */
    public function shouldFirst_replaceFirst()
    {
        // when
        $result = pattern('Foo')->replaced('Foo')->atMost()->first()->with('Bar');

        // then
        $this->assertSame('Bar', $result);
    }

    /**
     * @test
     */
    public function shouldFirstIgnore_Unmatched()
    {
        // when
        $result = pattern('Foo')->replaced('Bar')->atMost()->first()->with('Bar');

        // then
        $this->assertSame('Bar', $result);
    }

    /**
     * @test
     */
    public function shouldOnly2Throw_3occurrences()
    {
        // then
        $this->expectException(ReplacementExpectationFailedException::class);
        $this->expectExceptionMessage('Expected to perform at most 2 replacement(s), but at least 3 replacement(s) would have been performed');

        // when
        pattern('Foo')->replaced('Foo,Foo,Foo')->atMost()->only(2)->with('Bar');
    }

    /**
     * @test
     */
    public function shouldAtLeastFirst_throwForSingleMatch()
    {
        // then
        $this->expectException(ReplacementExpectationFailedException::class);
        $this->expectExceptionMessage('Expected to perform at most 1 replacement(s), but at least 2 replacement(s) would have been performed');

        // when
        pattern('Foo')->replaced('Foo,Foo')->atMost()->first()->with('Bar');
    }
}
