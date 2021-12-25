<?php
namespace Test\Feature\CleanRegex\Replaced\byGroupMap;

use PHPUnit\Framework\TestCase;
use Test\Utils\ExactExceptionMessage;
use TRegx\CleanRegex\Exception\GroupNotMatchedException;
use TRegx\CleanRegex\Exception\MissingReplacementKeyException;
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
    public function shouldReplaceByGroupMap()
    {
        // when
        $replaced = pattern('(?<value>\d+)[cm]?m')
            ->replaced('7cm, 46m, 114cm')
            ->byGroupMap('value', [
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
        pattern('Dracarys')->replaced('Dracarys')->byGroupMap(0, ['Valar' => 'Morghulis']);
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
        pattern('Foo')->replaced('Foo')->byGroupMap(0, $map);
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
        pattern('Foo')->replaced('Foo')->byGroupMap(0, $map);
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
        pattern('Jhonny')->replaced('Jhonny')->byGroupMap(0, $map);
    }

    /**
     * @test
     */
    public function shouldReplaceWithNumericString()
    {
        // when
        $result = pattern('420')->replaced('420')->byGroupMap(0, ['420' => '42']);

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
        pattern('Foo')->replaced('')->byGroupMap('2group', []);
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
        pattern('(?<group>foo)')->replaced('foo')->byGroupMap('missing', []);
    }

    /**
     * @test
     */
    public function shouldThrow_onNonExistingGroup_OnUnmatchedSubject()
    {
        // then
        $this->expectException(NonexistentGroupException::class);
        $this->expectExceptionMessage("Nonexistent group: 'unit'");

        // when
        pattern('Foo')->replaced('not matched')->byGroupMap('unit', []);
    }

    /**
     * @test
     */
    public function shouldThrow_groupNotMatch()
    {
        // then
        $this->expectException(GroupNotMatchedException::class);
        $this->expectExceptionMessage("Expected to replace with group #1, but the group was not matched");

        // when
        pattern('Foo(Bar)?')->replaced('Foo')->byGroupMap(1, []);
    }

    /**
     * @test
     */
    public function shouldThrow_groupNotMatch_middleGroup()
    {
        // then
        $this->expectException(GroupNotMatchedException::class);
        $this->expectExceptionMessage("Expected to replace with group 'bar', but the group was not matched");

        // when
        pattern('Foo(?<bar>Bar)?(Car)')
            ->replaced('FooCar')
            ->byGroupMap('bar', ['' => 'failure']);
    }

    /**
     * @test
     */
    public function shouldThrow_groupNotMatch_middleGroup_thirdIndex()
    {
        // then
        $this->expectException(GroupNotMatchedException::class);
        $this->expectExceptionMessage("Expected to replace with group 'bar', but the group was not matched");

        // when
        pattern('Foo(?<bar>Bar)?(?<car>Car)')
            ->replaced('FooBarCar FooBarCar FooCar')
            ->byGroupMap('bar', ['Bar' => 'Foo']);
    }

    /**
     * @test
     */
    public function shouldReplaceWithEmptyOccurrence()
    {
        // when
        $result = pattern('Foo()Cat')->replaced('"FooCat"')->byGroupMap(1, ['' => 'Bar']);

        // then
        $this->assertSame('"Bar"', $result);
    }

    /**
     * @test
     */
    public function shouldThrow_groupNotMatch_lastGroup()
    {
        // then
        $this->expectException(GroupNotMatchedException::class);
        $this->expectExceptionMessage("Expected to replace with group 'bar', but the group was not matched");

        // when
        pattern('Foo(?<bar>Bar)?')
            ->replaced('Foo')
            ->byGroupMap('bar', []);
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
        pattern('Foo')->replaced('Bar')->byGroupMap(1, ['Foo' => true]);
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
        pattern('(?<capital>F)our')->replaced('Four')->byGroupMap(1, ['O' => '1']);
    }

    /**
     * @test
     */
    public function shouldThrow_onMissingReplacementsKey_Empty()
    {
        // then
        $this->expectException(MissingReplacementKeyException::class);
        $this->expectExceptionMessage("Expected to replace value 'FooCar' by group #1 (''), but such key is not found in replacement map");

        // when
        pattern('Foo()Car')->replaced('FooCar')->byGroupMap(1, []);
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
        pattern('(?<capital>F)our')->replaced('Four')->byGroupMap(0, ['O' => '1']);
    }

    /**
     * @test
     */
    public function shouldThrow_ForMalformedPattern()
    {
        // then
        $this->expectException(MalformedPatternException::class);
        $this->expectExceptionMessage('Quantifier does not follow a repeatable item at offset 0');

        // when
        pattern('?')->replaced('Foo')->byGroupMap(0, []);
    }
}
