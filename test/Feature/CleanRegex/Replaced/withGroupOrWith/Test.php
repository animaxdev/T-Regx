<?php
namespace Test\Feature\CleanRegex\Replaced\withGroupOrWith;

use PHPUnit\Framework\TestCase;
use Test\Utils\ExactExceptionMessage;
use TRegx\CleanRegex\Exception\NonexistentGroupException;
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
    public function withGroup()
    {
        // when
        $replaced = pattern('(\d+)[cm]m')->replaced('13cm 18m 19cm')->withGroupOrWith(1, 'Unused');

        // then
        $this->assertSame('13 18m 19', $replaced);
    }

    /**
     * @test
     */
    public function withGroup_Named()
    {
        // when
        $replaced = pattern('\d+(?<unit>[cm]m)')->replaced('14cm 17m 19mm')->withGroupOrWith('unit', 'Unused');

        // then
        $this->assertSame('cm 17m mm', $replaced);
    }

    /**
     * @test
     */
    public function withGroup_NegativeIndex()
    {
        // then
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Group index must be a non-negative integer, but -6 given');

        // when
        pattern('\d+(?<unit>[cm]m)')->replaced('14cm 17m 19mm')->withGroupOrWith(-6, 'Unused');
    }

    /**
     * @test
     */
    public function withGroup_DigitString()
    {
        // then
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Group name must be an alphanumeric string, not starting with a digit, but '6digit' given");

        // when
        pattern('\d+(?<unit>[cm]m)')->replaced('14cm 17m 19mm')->withGroupOrWith('6digit', 'Unused');
    }

    /**
     * @test
     */
    public function withGroup_InvalidType()
    {
        // then
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Group index must be an integer or a string, but boolean (true) given");

        // when
        pattern('\d+(?<unit>[cm]m)')->replaced('14cm 17m 19mm')->withGroupOrWith(true, 'Unused');
    }

    /**
     * @test
     */
    public function withGroup_NonexistentGroupIndex()
    {
        // then
        $this->expectException(NonexistentGroupException::class);
        $this->expectExceptionMessage('Nonexistent group: #2');

        // when
        pattern('matched')->replaced('matched')->withGroupOrWith(2, 'Unused');
    }

    /**
     * @test
     */
    public function withGroup_NonexistentGroupName()
    {
        // then
        $this->expectException(NonexistentGroupException::class);
        $this->expectExceptionMessage("Nonexistent group: 'unit'");

        // when
        pattern('matched')->replaced('matched')->withGroupOrWith('unit', 'Unused');
    }

    /**
     * @test
     */
    public function withGroup_OnUnmatchedSubject_NonexistentGroupIndex()
    {
        // then
        $this->expectException(NonexistentGroupException::class);
        $this->expectExceptionMessage('Nonexistent group: #2');

        // when
        pattern('Foo')->replaced('not matched')->withGroupOrWith(2, 'Unused');
    }

    /**
     * @test
     */
    public function withGroup_OnUnmatchedSubject_NonexistentGroupName()
    {
        // then
        $this->expectException(NonexistentGroupException::class);
        $this->expectExceptionMessage("Nonexistent group: 'unit'");

        // when
        pattern('Foo')->replaced('not matched')->withGroupOrWith('unit', 'Unused');
    }

    /**
     * @test
     */
    public function shouldNotThrowForLastEmptyGroup()
    {
        // when
        $result = pattern('(Foo):()')->replaced('Bar')->withGroupOrWith(2, 'Unused');

        // then
        $this->assertSame('Bar', $result);
    }

    /**
     * @test
     */
    public function shouldCallSafe()
    {
        // then
        $this->expectException(MalformedPatternException::class);

        // when
        pattern(')')->replaced('Bar')->withGroupOrWith(2, 'Unused');
    }

    /**
     * @test
     */
    public function shouldThrow_ForUnmatchedGroup()
    {
        // when
        $result = pattern('Foo(Bar)?')->replaced('<Foo>, <FooBar>')->withGroupOrWith(1, 'Else');

        // then
        $this->assertSame('<Else>, <Bar>', $result);
    }

    /**
     * @test
     */
    public function shouldReplace_ForUnmatchedGroup_Middle()
    {
        // when
        $result = pattern('Foo(Bar)?(Cat)')->replaced('"FooCat"')->withGroupOrWith(1, 'Door');

        // then
        $this->assertSame('"Door"', $result);
    }
}
