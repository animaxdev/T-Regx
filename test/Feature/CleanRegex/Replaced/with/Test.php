<?php
namespace Test\Feature\CleanRegex\Replaced\with;

use PHPUnit\Framework\TestCase;
use TRegx\Exception\MalformedPatternException;

/**
 * @coversNothing
 */
class Test extends TestCase
{
    /**
     * @test
     */
    public function with()
    {
        // when
        $replaced = pattern('\d+')->replaced('127.0.0.1')->with('X');

        // then
        $this->assertSame('X.X.X.X', $replaced);
    }

    /**
     * @test
     */
    public function with_UsingPcreReferencesDollar()
    {
        // when
        $replaced = pattern('\d+')->replaced('127.0.0.1')->with('$1');

        // then
        $this->assertSame('$1.$1.$1.$1', $replaced);
    }

    /**
     * @test
     */
    public function with_UsingPcreReferencesBackslash()
    {
        // when
        $replaced = pattern('\d+')->replaced('127.0.0.1')->with('\1');

        // then
        $this->assertSame('\1.\1.\1.\1', $replaced);
    }

    /**
     * @test
     */
    public function only_MalformedPattern()
    {
        // then
        $this->expectException(MalformedPatternException::class);
        $this->expectExceptionMessage('Quantifier does not follow a repeatable item at offset 0');

        // when
        pattern('?')->replaced('Foo')->only(0)->with('Bar');
    }
}
