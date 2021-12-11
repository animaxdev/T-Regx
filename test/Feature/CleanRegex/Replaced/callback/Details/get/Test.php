<?php
namespace Test\Feature\TRegx\CleanRegex\Replaced\callback\Details\get;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Exception\GroupNotMatchedException;
use TRegx\CleanRegex\Exception\NonexistentGroupException;
use TRegx\CleanRegex\Match\Details\Detail;

class Test extends TestCase
{
    /**
     * @test
     */
    public function shouldReplaceWithMatchedGroup()
    {
        // when
        $result = pattern('([A-Z])[a-z]+')
            ->replaced('Stark, Eddard')
            ->callback(function (Detail $detail) {
                return $detail->get(1);
            });

        // then
        $this->assertSame('S, E', $result);
    }

    /**
     * @test
     */
    public function shouldReplaceWithGroup_notMatched_index()
    {
        // given
        $this->expectException(GroupNotMatchedException::class);
        $this->expectExceptionMessage("Expected to get group #1, but the group was not matched");

        // when
        pattern('Foo(Bar)?')->replaced('Foo')->callback(function (Detail $detail) {
            // then
            $detail->get(1);
        });
    }

    /**
     * @test
     */
    public function shouldThrowForUnmatchedGroup()
    {
        // given
        $this->expectException(GroupNotMatchedException::class);
        $this->expectExceptionMessage("Expected to get group 'domain', but the group was not matched");

        // when
        pattern('(?<domain>domain)?')
            ->replaced('')
            ->callback(function (Detail $detail) {
                // then
                $detail->get('domain');
            });
    }

    /**
     * @test
     */
    public function shouldThrowForInvalidGroup()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Group name must be an alphanumeric string, not starting with a digit, but '2group' given");

        // when
        pattern('Foo')->replaced('Foo')->first()->callback(function (Detail $detail) {
            $detail->get('2group');
        });
    }

    /**
     * @test
     */
    public function shouldThrowForInvalidGroup_NumericString()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Group name must be an alphanumeric string, not starting with a digit, but '0' given");

        // when
        pattern('Foo')->replaced('Foo')->first()->callback(function (Detail $detail) {
            $detail->get('0');
        });
    }

    /**
     * @test
     */
    public function shouldGetLastGroup()
    {
        // when
        pattern('Bar(Foo)?')->replaced('BarFoo')->first()->callback(function (Detail $detail) {
            // then
            $this->assertSame('Foo', $detail->get(1));

            // clean
            return '';
        });
    }

    /**
     * @test
     */
    public function shouldGetMiddleGroupNotMatched()
    {
        // then
        $this->expectException(GroupNotMatchedException::class);
        $this->expectExceptionMessage('Expected to get group #1, but the group was not matched');

        // when
        pattern('Foo(Bar)?(Cat)')->replaced('FooCat')->first()->callback(function (Detail $detail) {
            // then
            $detail->get(1);
        });
    }

    /**
     * @test
     */
    public function shouldThrowForNonExistentGroup()
    {
        // then
        $this->expectException(NonexistentGroupException::class);
        $this->expectExceptionMessage('Nonexistent group: #1');

        // when
        pattern('Daenerys')->replaced('Daenerys')->first()->callback(function (Detail $detail) {
            // then
            $detail->get(1);
        });
    }

    /**
     * @test
     */
    public function shouldThrowForNonExistentGroupNamed()
    {
        // then
        $this->expectException(NonexistentGroupException::class);
        $this->expectExceptionMessage("Nonexistent group: 'missing'");

        // when
        pattern('Daenerys')->replaced('Daenerys')->first()->callback(function (Detail $detail) {
            // then
            $detail->get('missing');
        });
    }

    /**
     * @test
     */
    public function shouldGetMiddleGroupEmpty()
    {
        // when
        pattern('Foo()(Bar)')->replaced('FooBar')->first()->callback(function (Detail $detail) {
            // then
            $this->assertSame('', $detail->get(1));

            // clean
            return '';
        });
    }

    /**
     * @test
     */
    public function shouldGetLastGroupEmpty()
    {
        // when
        pattern('Bar()')->replaced('Bar')->first()->callback(function (Detail $detail) {
            // then
            $this->assertSame('', $detail->get(1));

            // clean
            return '';
        });
    }
}
