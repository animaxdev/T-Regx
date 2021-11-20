<?php
namespace Test\Feature\CleanRegex\Replaced\callback\Details;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Match\Details\Detail;

class Test extends TestCase
{
    /**
     * @test
     */
    public function shouldGetSubject()
    {
        // given
        pattern('(?<matched>Foo)(?<unmatched>Bar)?')
            ->replaced('Hello:Foo')
            ->callback(function (Detail $detail) {
                $this->assertSame('Hello:Foo', $detail->subject());

                // cleanup
                return '';
            });
    }
}
