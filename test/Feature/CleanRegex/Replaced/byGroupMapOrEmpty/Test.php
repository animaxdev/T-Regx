<?php
namespace Test\Feature\CleanRegex\Replaced\byGroupMapOrEmpty;

use PHPUnit\Framework\TestCase;
use Test\Utils\ExactExceptionMessage;
use TRegx\CleanRegex\Exception\MissingReplacementKeyException;
use TRegx\CleanRegex\Exception\NonexistentGroupException;

/**
 * @coversNothing
 */
class Test extends TestCase
{
    use ExactExceptionMessage;

    /**
     * @test
     */
    public function shouldReplaceByGroupMap()
    {
        // when
        $replaced = pattern('(?<value>\d+)[cm]?m')
            ->replaced('7cm, 46m, 114cm')
            ->byGroupMapOrEmpty('value', [
                '7'   => "Joffrey's Dick",
                '46'  => 'Drogon length',
                '114' => "Long Claw's length",
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
        $this->expectExceptionMessage("Expected to replace value 'Dracarys' by group #0 ('Dracarys'), but such key is not found in replacement map");

        // when
        pattern('Dracarys')->replaced('Dracarys')->byGroupMapOrEmpty(0, ['Valar' => 'Morghulis']);
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
        pattern('Foo')->replaced('Foo')->byGroupMapOrEmpty(0, $map);
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
        pattern('Foo')->replaced('Foo')->byGroupMapOrEmpty(0, $map);
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
        pattern('Jhonny')->replaced('Jhonny')->byGroupMapOrEmpty(0, $map);
    }

    /**
     * @test
     */
    public function shouldReplaceWithNumericString()
    {
        // when
        $result = pattern('420')->replaced('420')->byGroupMapOrEmpty(0, ['420' => '42']);

        // then
        $this->assertSame('42', $result);
    }

    /**
     * @test
     */
    public function shouldThrow_onInvalidGroupName()
    {
        // then
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Group name must be an alphanumeric string, not starting with a digit, but '2group' given");

        // when
        pattern('Foo')->replaced('')->byGroupMapOrEmpty('2group', []);
    }

    /**
     * @test
     */
    public function shouldThrow_onNonExistingGroup()
    {
        // then
        $this->expectException(NonexistentGroupException::class);
        $this->expectExceptionMessage("Nonexistent group: 'missing'");

        // when
        pattern('(?<group>foo)')->replaced('foo')->byGroupMapOrEmpty('missing', []);
    }

    /**
     * @test
     */
    public function shouldReplace_withEmpty_forUnmatchedGroup()
    {
        // when
        $result = pattern('Foo(Bar)?')->replaced('Value:Foo, Value:FooBar')->byGroupMapOrEmpty(1, ['Bar' => 'Cat']);

        // then
        $this->assertSame('Value:, Value:Cat', $result);
    }

    /**
     * @test
     */
    public function shouldReplace_withEmpty_forUnmatchedGroup_middleGroup()
    {
        // when
        $result = pattern('Foo(?<bar>Bar)?(Car)')->replaced('"FooCar"')->byGroupMapOrEmpty('bar', ['' => 'failure']);

        // then
        $this->assertSame('""', $result);
    }

    /**
     * @test
     */
    public function shouldThrow_groupNotMatch_middleGroup_thirdIndex()
    {
        // when
        $result = pattern('Foo(?<bar>Bar)?(?<car>Car)')->replaced('FooBarCar FooBarCar "FooCar"')->byGroupMapOrEmpty('bar', ['Bar' => 'Door']);

        // then
        $this->assertSame('Door Door ""', $result);
    }

    /**
     * @test
     */
    public function shouldReplaceWithEmptyOccurrence()
    {
        // when
        $result = pattern('Foo()Cat')->replaced('"FooCat"')->byGroupMapOrEmpty(1, ['' => 'Bar']);

        // then
        $this->assertSame('"Bar"', $result);
    }

    /**
     * @test
     */
    public function shouldIgnore_groupNotMatch_lastGroup()
    {
        // when
        $result = pattern('Value:Foo(?<bar>Bar)?')
            ->replaced('Value:Foo, Value:FooBar')
            ->byGroupMapOrEmpty('bar', ['Bar' => 'Cat']);

        // then
        $this->assertSame(', Cat', $result);
    }

    /**
     * @test
     */
    public function shouldThrow_onInvalidValue()
    {
        // then
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Invalid replacement map value. Expected string, but boolean (true) given");

        // when
        pattern('Foo')->replaced('Bar')->byGroupMapOrEmpty(1, ['Foo' => true]);
    }

    /**
     * @test
     */
    public function shouldThrow_onMissingReplacementsKey()
    {
        // then
        $this->expectException(MissingReplacementKeyException::class);
        $this->expectExceptionMessage("Expected to replace value 'Four' by group #1 ('F'), but such key is not found in replacement map");

        // when
        pattern('(?<capital>F)our')->replaced('Four')->byGroupMapOrEmpty(1, ['O' => '1']);
    }

    /**
     * @test
     */
    public function shouldThrow_onMissingReplacementsKey_Group0()
    {
        // then
        $this->expectException(MissingReplacementKeyException::class);
        $this->expectExceptionMessage("Expected to replace value 'Four' by group #0 ('Four'), but such key is not found in replacement map");

        // when
        pattern('(?<capital>F)our')->replaced('Four')->byGroupMapOrEmpty(0, ['O' => '1']);
    }
}
