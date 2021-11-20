<?php
namespace Test\Feature\CleanRegex\Replaced\byGroupMapOrWith;

use PHPUnit\Framework\TestCase;
use Test\Utils\ExactExceptionMessage;
use TRegx\CleanRegex\Exception\GroupNotMatchedException;
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
            ->byGroupMapOrWith('value', [
                '7'   => "Joffrey's Dick",
                '46'  => 'Drogon length',
                '114' => "Long Claw's length",
            ], 'Unused');

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
        pattern('Dracarys')->replaced('Dracarys')->byGroupMapOrWith(0, ['Valar' => 'Morghulis'], 'Unused');
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
        pattern('Foo')->replaced('Foo')->byGroupMapOrWith(0, $map, 'Unused');
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
        pattern('Foo')->replaced('Foo')->byGroupMapOrWith(0, $map, 'Unused');
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
        pattern('Jhonny')->replaced('Jhonny')->byGroupMapOrWith(0, $map, 'Unused');
    }

    /**
     * @test
     */
    public function shouldReplaceWithNumericString()
    {
        // when
        $result = pattern('420')->replaced('420')->byGroupMapOrWith(0, ['420' => '42'], 'Unused');

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
        pattern('Foo')->replaced('')->byGroupMapOrWith('2group', [], 'Unused');
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
        pattern('(?<group>foo)')->replaced('foo')->byGroupMapOrWith('missing', [], 'Unused');
    }

    /**
     * @test
     */
    public function shouldReplace_groupNotMatch()
    {
        // when
        $result = pattern('Foo(Bar)?')->replaced('Value:Foo, Value:FooBar')->byGroupMapOrWith(1, ['Bar' => 'Cat'], 'Else');

        // then
        $this->assertSame('Value:Else, Value:Cat', $result);
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
            ->byGroupMapOrWith('bar', ['' => 'failure'], 'Unused');
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
            ->byGroupMapOrWith('bar', ['Bar' => 'Foo'], 'Unused');
    }

    /**
     * @test
     */
    public function shouldReplace_groupNotMatch_lastGroup()
    {
        // when
        $result = pattern('Value:Foo(?<bar>Bar)?')
            ->replaced('Value:Foo, Value:FooBar')
            ->byGroupMapOrWith('bar', ['Bar' => 'Cat'], 'Else');

        // then
        $this->assertSame('Else, Cat', $result);
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
        pattern('Foo')->replaced('Bar')->byGroupMapOrWith(1, ['Foo' => true], 'Unused');
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
        pattern('(?<capital>F)our')->replaced('Four')->byGroupMapOrWith(1, ['O' => '1'], 'Unused');
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
        pattern('(?<capital>F)our')->replaced('Four')->byGroupMapOrWith(0, ['O' => '1'], 'Unused');
    }
}
