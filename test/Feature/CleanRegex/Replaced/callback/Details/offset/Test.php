<?php
namespace Test\Feature\CleanRegex\Replaced\callback\Details\offset;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Match\Details\Detail;

class Test extends TestCase
{
    /**
     * @test
     */
    public function shouldGetOffset()
    {
        // given
        $offsets = [];
        $byteOffsets = [];

        // when
        pattern('(Tomek|Kamil)')
            ->replaced('€€€€, Tomek i Kamil')
            ->callback(function (Detail $detail) use (&$offsets, &$byteOffsets) {
                // when
                $offsets[] = $detail->offset();
                $byteOffsets[] = $detail->byteOffset();

                // clean
                return '';
            });

        // then
        $this->assertSame([6, 14], $offsets);
        $this->assertSame([14, 22], $byteOffsets);
    }
}
