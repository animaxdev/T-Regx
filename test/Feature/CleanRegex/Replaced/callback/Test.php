<?php
namespace Test\Feature\CleanRegex\Replaced\callback;

use PHPUnit\Framework\TestCase;
use Test\Utils\Functions;
use TRegx\CleanRegex\Exception\InvalidReplacementException;

/**
 * @coversNothing
 */
class Test extends TestCase
{
    /**
     * @test
     */
    public function testCallback()
    {
        // when
        $replaced = pattern('\w+')->replaced('Joffrey, Cersei, Ilyn Payne, The Hound')->callback(Functions::charAt(0));

        // then
        $this->assertSame('J, C, I P, T H', $replaced);
    }

    /**
     * @test
     */
    public function testCallback_nonStringReturn()
    {
        // then
        $this->expectException(InvalidReplacementException::class);
        $this->expectExceptionMessage('Invalid callback() callback return type. Expected string, but array (0) given');

        // when
        pattern('\w+')->replaced('Foo')->callback(Functions::constant([]));
    }
}
