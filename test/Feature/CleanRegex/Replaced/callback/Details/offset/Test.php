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
        pattern('(Tome|Kamy)k')
            ->replaced('€€€€, Tomek i Kamyk')
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
        pattern('(Tońe|Kamy)k')
            ->replaced('€€€€, Tońek i Kamyk')
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

    /**
     * @test
     */
    public function shouldGetLength()
    {
        // given
        $lengths = [];
        $byteLengths = [];

        // when
        pattern('(Tońe|Kamy)k')
            ->replaced('€€€€, Tońek i Kamyk')
            ->callback(function (Detail $detail) use (&$lengths, &$byteLengths) {
                // when
                $byteLengths[] = $detail->textByteLength();
                $lengths[] = $detail->textLength();

                // clean
                return '';
            });

        // then
        $this->assertSame([6, 5], $byteLengths);
        $this->assertSame([5, 5], $lengths);
    }
}
