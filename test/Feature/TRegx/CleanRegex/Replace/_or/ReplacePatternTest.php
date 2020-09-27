<?php
namespace Test\Feature\TRegx\CleanRegex\Replace\_or;

use PHPUnit\Framework\TestCase;
use Test\Utils\CustomSubjectException;
use Test\Utils\Functions;
use TRegx\CleanRegex\Exception\InvalidReturnValueException;

class ReplacePatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldReturn_with()
    {
        // when
        $result = pattern('Foo')->replace('Bar')->first()->otherwiseReturning('otherwise')->with('');

        // then
        $this->assertEquals('otherwise', $result);
    }

    /**
     * @test
     */
    public function shouldReturn_withReferences()
    {
        // when
        $result = pattern('Foo')->replace('Bar')->first()->otherwiseReturning('otherwise')->withReferences('');

        // then
        $this->assertEquals('otherwise', $result);
    }

    /**
     * @test
     */
    public function shouldReturn_with_orElse()
    {
        // when
        $result = pattern('Foo')
            ->replace('Bar')
            ->first()
            ->otherwise(function (string $subject) {
                $this->assertEquals('Bar', $subject);
                return 'otherwise';
            })
            ->with('');

        // then
        $this->assertEquals('otherwise', $result);
    }

    /**
     * @test
     */
    public function shouldThrow_with_orElse_returnNull()
    {
        // then
        $this->expectException(InvalidReturnValueException::class);
        $this->expectExceptionMessage('Invalid otherwise() callback return type. Expected string, but null given');

        // when
        pattern('Foo')
            ->replace('Bar')
            ->first()
            ->otherwise(function (string $subject) {
                $this->assertEquals('Bar', $subject);
                return null;
            })
            ->with('');
    }

    /**
     * @test
     */
    public function shouldThrow_with_orThrow()
    {
        // given
        $replacePattern = pattern('Foo')->replace('Bar')->first()->otherwiseThrowing(CustomSubjectException::class);

        // when
        try {
            $replacePattern->with('');
        } catch (CustomSubjectException $e) {
            // then
            $this->assertEquals("Replacements were supposed to be performed, but subject doesn't match the pattern", $e->getMessage());
            $this->assertEquals('Bar', $e->subject);
        }
    }

    /**
     * @test
     */
    public function shouldReturn_callback()
    {
        // given
        $replacePattern = pattern('Foo')->replace('Bar')->first();

        // when
        $result = $replacePattern->otherwiseReturning('otherwise')->callback(Functions::constant(''));

        // then
        $this->assertEquals('otherwise', $result);
    }

    /**
     * @test
     */
    public function shouldReturn_by_map()
    {
        // given
        $replacePattern = pattern('Foo')->replace('Bar')->first();

        // when
        $result = $replacePattern->otherwiseReturning('otherwise')->by()->map([]);

        // then
        $this->assertEquals('otherwise', $result);
    }

    /**
     * @test
     */
    public function shouldReturn_by_group_map()
    {
        // given
        $replacePattern = pattern('(Foo)')->replace('Bar')->first();

        // when
        $result = $replacePattern->otherwiseReturning('otherwise')->by()->group(1)->map([])->orThrow();

        // then
        $this->assertEquals('otherwise', $result);
    }
}
