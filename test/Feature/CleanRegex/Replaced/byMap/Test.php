<?php
namespace Test\Feature\CleanRegex\Replaced\byMap;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Exception\MissingReplacementKeyException;

/**
 * @coversNothing
 */
class Test extends TestCase
{
    /**
     * @test
     */
    public function shouldReplaceByMap()
    {
        // when
        $replaced = pattern('\d+[cm]?m')
            ->replaced('7cm, 46m, 114cm')
            ->byMap([
                '7cm'   => "Joffrey's Dick",
                '46m'   => 'Drogon length',
                '114cm' => "Long Claw's length",
            ]);

        // then
        $this->assertSame("Joffrey's Dick, Drogon length, Long Claw's length", $replaced);
    }

    /**
     * @test
     */
    public function shouldThrowForMissingReplacement()
    {
        // then
        $this->expectException(MissingReplacementKeyException::class);
        $this->expectExceptionMessage("Expected to replace value 'Dracarys', but such key is not found in replacement map");

        // when
        pattern('Dracarys')->replaced('Dracarys')->byMap(['Valar' => 'Morghulis']);
    }

    /**
     * @test
     */
    public function shouldThrowForInvalidValueBoolean()
    {
        // given
        $map = ['Foo' => true];

        // then
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Invalid replacement map value. Expected string, but boolean (true) given");

        // when
        pattern('Foo')->replaced('Foo')->byMap($map);
    }

    /**
     * @test
     */
    public function shouldThrowForInvalidValueInteger()
    {
        // given
        $map = ['Foo' => 42];

        // then
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Invalid replacement map value. Expected string, but integer (42) given");

        // when
        pattern('Foo')->replaced('Foo')->byMap($map);
    }

    /**
     * @test
     */
    public function shouldThrowForInvalidValueNotMatched()
    {
        // given
        $map = ['Jhonny' => 'Walker', 'Jack' => "Daniel's", 'Mark' => 69];

        // then
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Invalid replacement map value. Expected string, but integer (69) given");

        // when
        pattern('Jhonny')->replaced('Jhonny')->byMap($map);
    }

    /**
     * @test
     */
    public function shouldReplaceWithNumericString()
    {
        // when
        $result = pattern('420')->replaced('420')->byMap(['420' => '69']);

        // then
        $this->assertSame('69', $result);
    }
}
