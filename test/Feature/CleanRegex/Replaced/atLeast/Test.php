<?php
namespace Test\Feature\CleanRegex\Replaced\atLeast;

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
    public function testAtLeast_Only2()
    {
        // when
        $result = pattern('Foo')->replaced('Foo,Foo')->atLeast()->only(2)->with('Bar');

        // then
        $this->assertSame('Bar,Bar', $result);
    }

    /**
     * @test
     */
    public function testAtLeast_Only1()
    {
        // when
        $result = pattern('Foo')->replaced('Foo,Foo')->atLeast()->only(1)->with('Bar');

        // then
        $this->assertSame('Bar,Foo', $result);
    }

    /**
     * @test
     */
    public function testAtLeast_first()
    {
        // when
        $result = pattern('Foo')->replaced('Foo,Foo')->atLeast()->first()->with('Bar');

        // then
        $this->assertSame('Bar,Foo', $result);
    }

    /**
     * @test
     */
    public function shouldAtLeastOnly2_throwForSingleMatch()
    {
        // then
        $this->expectException(ReplacementExpectationFailedException::class);
        $this->expectExceptionMessage('Expected to perform at least 2 replacement(s), but 1 replacement(s) were actually performed');

        // when
        pattern('Foo')->replaced('Foo')->atLeast()->only(2)->with('Bar');
    }

    /**
     * @test
     */
    public function shouldAtLeastFirst_throwForSingleMatch()
    {
        // then
        $this->expectException(ReplacementExpectationFailedException::class);
        $this->expectExceptionMessage('Expected to perform at least 1 replacement(s), but 0 replacement(s) were actually performed');

        // when
        pattern('Foo')->replaced('Bar')->atLeast()->first()->with('Bar');
    }
}
