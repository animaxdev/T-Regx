<?php
namespace Test\Feature\CleanRegex\Replaced\limits;

use PHPUnit\Framework\TestCase;
use Test\Utils\ExactExceptionMessage;
use TRegx\Exception\MalformedPatternException;

/**
 * @coversNothing
 */
class Test extends TestCase
{
    use ExactExceptionMessage;

    /**
     * @test
     */
    public function all_with()
    {
        // when
        $replaced = pattern('\d+')->replaced('127.0.0.1')->all()->with('X');

        // then
        $this->assertSame('X.X.X.X', $replaced);
    }

    /**
     * @test
     */
    public function all_withReferences()
    {
        // when
        $replaced = pattern('(\d+)')->replaced('127.0.0.1')->all()->withReferences('<$1>');

        // then
        $this->assertSame('<127>.<0>.<0>.<1>', $replaced);
    }

    /**
     * @test
     */
    public function first_with()
    {
        // when
        $replaced = pattern('\d+')->replaced('127.0.0.1')->first()->with('X');

        // then
        $this->assertSame('X.0.0.1', $replaced);
    }

    /**
     * @test
     */
    public function first_withReferences()
    {
        // when
        $replaced = pattern('(\d+)')->replaced('127.0.0.1')->first()->withReferences('<$1>');

        // then
        $this->assertSame('<127>.0.0.1', $replaced);
    }

    /**
     * @test
     */
    public function shouldThrowForNegativeLimit()
    {
        // then
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Negative limit: -4');

        // when
        pattern('\d+')->replaced('127.0.0.1')->only(-4);
    }

    /**
     * @test
     */
    public function only_with()
    {
        // when
        $replaced = pattern('\d+')->replaced('127.0.0.1')->only(3)->with('X');

        // then
        $this->assertSame('X.X.X.1', $replaced);
    }

    /**
     * @test
     */
    public function only_withReferences()
    {
        // when
        $replaced = pattern('(\d+)')->replaced('127.0.0.1')->only(2)->withReferences('<$1>');

        // then
        $this->assertSame('<127>.<0>.0.1', $replaced);
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
        pattern('?')->replaced('Foo')->only(0)->withReferences('Bar');
    }
}
