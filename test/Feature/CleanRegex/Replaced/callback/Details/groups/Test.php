<?php
namespace Test\Feature\CleanRegex\Replaced\callback\Details\groups;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Match\Details\Detail;
use TRegx\CleanRegex\Replaced\ReplaceDetail;

class Test extends TestCase
{
    /**
     * @test
     */
    public function shouldGetGroupsNames()
    {
        // given
        pattern('(zero) (?<existing>first) and (?<two_existing>second)')
            ->replaced('zero first and second')
            ->all()
            ->callback(function (ReplaceDetail $detail) {
                // when
                $groupNames = $detail->groups()->names();
                $namedGroups = $detail->namedGroups()->names();

                // then
                $this->assertSame([null, 'existing', 'two_existing'], $groupNames);
                $this->assertSame(['existing', 'two_existing'], $namedGroups);

                // clean up
                return '';
            });
    }

    /**
     * @test
     */
    public function shouldCount()
    {
        // given
        pattern('(zero) (?<existing>first) and (?<two_existing>second)')
            ->replaced('zero first and second')
            ->all()
            ->callback(function (Detail $detail) {
                // when
                $groups = $detail->groups()->count();
                $namedGroups = $detail->namedGroups()->count();

                // then
                $this->assertSame(3, $groups);
                $this->assertSame(2, $namedGroups);

                // clean up
                return '';
            });
    }
}
