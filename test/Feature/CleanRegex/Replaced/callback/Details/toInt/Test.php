<?php
namespace Test\Feature\CleanRegex\Replaced\callback\Details\toInt;

use PHPUnit\Framework\TestCase;
use Test\Utils\ExactExceptionMessage;
use TRegx\CleanRegex\Exception\IntegerFormatException;
use TRegx\CleanRegex\Exception\IntegerOverflowException;
use TRegx\CleanRegex\Match\Details\Detail;
use function pattern;

class Test extends TestCase
{
    use ExactExceptionMessage;

    /**
     * @test
     */
    public function shouldGetInteger()
    {
        // given
        pattern('194')->replaced('194')->callback(function (Detail $detail) {
            $this->assertSame(194, $detail->toInt());

            // cleanup
            return '';
        });
    }

    /**
     * @test
     */
    public function shouldGetIntegerBase11()
    {
        // given
        pattern('1abc')->replaced('1abc')->callback(function (Detail $detail) {
            $this->assertSame(4042, $detail->toInt(13));

            // cleanup
            return '';
        });
    }

    /**
     * @test
     */
    public function shouldThrowForInvalidArgumentBase9()
    {
        // given
        pattern('9')->replaced('9')->callback(function (Detail $detail) {
            // then
            $this->expectException(IntegerFormatException::class);
            $this->expectExceptionMessage("Expected to parse '9', but it is not a valid integer in base 9");

            // when
            $detail->toInt(9);

            // cleanup
            return '';
        });
    }

    /**
     * @test
     */
    public function shouldThrow_forOverflownInteger()
    {
        // then
        $this->expectException(IntegerOverflowException::class);
        $this->expectExceptionMessage("Expected to parse '-9223372036854775809', but it exceeds integer size on this architecture in base 10");

        // given
        pattern('-\d+')->replaced('-9223372036854775809')
            ->callback(function (Detail $detail) {
                // when
                return $detail->toInt();
            });
    }
}
