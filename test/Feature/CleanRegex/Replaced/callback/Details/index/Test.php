<?php
namespace Test\Feature\CleanRegex\Replaced\callback\Details\index;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Match\Details\Detail;

class Test extends TestCase
{
    /**
     * @test
     */
    public function shouldGetIndexOfTheFirstCall()
    {
        // given
        pattern('\d+')
            ->replaced('111-222-333')
            ->first()
            ->callback(function (Detail $detail) {
                // when
                $this->assertSame(0, $detail->index());

                // clean up
                return '';
            });
    }

    /**
     * @test
     */
    public function shouldGetConsecutiveIndices()
    {
        // given
        $indexes = [];

        // when
        pattern('\d+')
            ->replaced('111-222-333')
            ->all()
            ->callback(function (Detail $detail) use (&$indexes) {
                $indexes[] = $detail->index();

                // clean up
                return '';
            });

        // then
        $this->assertSame([0, 1, 2], $indexes);
    }
}
