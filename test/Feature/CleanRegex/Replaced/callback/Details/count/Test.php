<?php
namespace Test\Feature\CleanRegex\Replaced\callback\Details\count;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Match\Details\Detail;

class Test extends TestCase
{
    /**
     * @test
     */
    public function shouldCount()
    {
        // given
        pattern('(zero) (?<existing>first) and (?<two_existing>second)')
            ->replaced('zero first and second')
            ->callback(function (Detail $detail) {
                // when
                $count = $detail->groups()->count();
                $countNamed = $detail->namedGroups()->count();

                // then
                $this->assertSame(3, $count);
                $this->assertSame(2, $countNamed);

                // clean up
                return '';
            });
    }
}
