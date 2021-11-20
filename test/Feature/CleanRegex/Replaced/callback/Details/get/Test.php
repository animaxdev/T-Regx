<?php
namespace Test\Feature\TRegx\CleanRegex\Replaced\callback\Details\get;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Exception\GroupNotMatchedException;
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
            return $detail->get(1);
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
                return $detail->get('domain');
            });
    }
}
