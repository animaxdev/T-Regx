<?php
namespace Test\Feature\CleanRegex\Replaced\callback\Details\groupsCount;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Match\Details\Detail;

class Test extends TestCase
{
    /**
     * @test
     */
    public function shouldGetGroupsCount()
    {
        // given
        pattern('(?<one>first) and (second)')
            ->replaced('first and second')
            ->callback(function (Detail $detail) {
                $this->assertSame(2, $detail->groupsCount());

                // clean up
                return '';
            });
    }

    /**
     * @test
     */
    public function shouldGetGroupsCount_lastEmpty()
    {
        // given
        pattern('(?<one>first) and (second)?')
            ->replaced('first and ')
            ->callback(function (Detail $detail) {
                $this->assertSame(2, $detail->groupsCount());

                // clean up
                return '';
            });
    }
}
