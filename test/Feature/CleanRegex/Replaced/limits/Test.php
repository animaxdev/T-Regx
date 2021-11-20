<?php
namespace Test\Feature\CleanRegex\Replaced\limits;

use PHPUnit\Framework\TestCase;
use Test\Utils\ExactExceptionMessage;
use Test\Utils\Functions;
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
    public function all_callback()
    {
        // when
        $replaced = pattern('\d+')->replaced('127.230.35.10')->all()->callback(Functions::charAt(0));

        // then
        $this->assertSame('1.2.3.1', $replaced);
    }

    /**
     * @test
     */
    public function all_withGroup()
    {
        // when
        $replaced = pattern('<(\d+)>')->replaced('<127>.230.<36>.10')->all()->withGroup(1);

        // then
        $this->assertSame('127.230.36.10', $replaced);
    }

    /**
     * @test
     */
    public function all_byMap()
    {
        // when
        $replaced = pattern('Foo|Cat')->replaced('Foo,Cat,Foo')->all()->byMap(['Foo' => 'Bar', 'Cat' => 'Dog']);

        // then
        $this->assertSame('Bar,Dog,Bar', $replaced);
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
    public function first_callback()
    {
        // when
        $replaced = pattern('\d+')->replaced('127.230.35.10')->first()->callback(Functions::charAt(0));

        // then
        $this->assertSame('1.230.35.10', $replaced);
    }

    /**
     * @test
     */
    public function first_withGroup()
    {
        // when
        $replaced = pattern('!(\d+)!')->replaced('!123! !345!')->first()->withGroup(1);

        // then
        $this->assertSame('123 !345!', $replaced);
    }

    /**
     * @test
     */
    public function first_byMap()
    {
        // when
        $replaced = pattern('Lorem')->replaced('Lorem,Lorem')->first()->byMap(['Lorem' => 'Ipsum']);

        // then
        $this->assertSame('Ipsum,Lorem', $replaced);
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

    /**
     * @test
     */
    public function only_callback()
    {
        // when
        $replaced = pattern('\d+')->replaced('127.230.35.10')->only(2)->callback(Functions::charAt(0));

        // then
        $this->assertSame('1.2.35.10', $replaced);
    }

    /**
     * @test
     */
    public function only_withGroup()
    {
        // when
        $replaced = pattern('!(\d+)!')->replaced('!123!, !345!, !678!')->only(2)->withGroup(1);

        // then
        $this->assertSame('123, 345, !678!', $replaced);
    }

    /**
     * @test
     */
    public function only_byMap()
    {
        // when
        $replaced = pattern('Lorem')->replaced('Lorem,Lorem,Lorem')->only(2)->byMap(['Lorem' => 'Ipsum']);

        // then
        $this->assertSame('Ipsum,Ipsum,Lorem', $replaced);
    }
}
