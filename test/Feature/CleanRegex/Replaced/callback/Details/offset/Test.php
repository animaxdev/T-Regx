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

    /**
     * @test
     */
    public function shouldGetTail()
    {
        // given
        $tails = [];
        $byteTails = [];

        // when
        pattern('(Tomeł|Kamil)')
            ->replaced('€€€€, Tomeł i Kamil')
            ->callback(function (Detail $detail) use (&$tails, &$byteTails) {
                // when
                $tails[] = $detail->tail();
                $byteTails[] = $detail->byteTail();

                // clean
                return '';
            });

        // then
        $this->assertSame([11, 19], $tails);
        $this->assertSame([20, 28], $byteTails);
    }
}
