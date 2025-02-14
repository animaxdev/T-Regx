<?php
namespace Test\Unit\TRegx\SafeRegex\Internal\Errors\Errors;

use PHPUnit\Framework\TestCase;
use Test\Utils\ThrowsForUnmockedMethods;
use TRegx\SafeRegex\Exception\PregException;
use TRegx\SafeRegex\Internal\Errors\Errors\BothHostError;
use TRegx\SafeRegex\Internal\Errors\Errors\CompileError;
use TRegx\SafeRegex\Internal\Errors\Errors\IrrelevantCompileError;
use TRegx\SafeRegex\Internal\Errors\Errors\RuntimeError;

/**
 * @covers \TRegx\SafeRegex\Internal\Errors\Errors\BothHostError
 */
class BothHostErrorTest extends TestCase
{
    use ThrowsForUnmockedMethods;

    /**
     * @test
     */
    public function shouldAlwaysBeTreatedAsOccurred(): void
    {
        // given
        $hostError = new BothHostError(new IrrelevantCompileError(), new RuntimeError(2));

        // when
        $occurred = $hostError->occurred();

        // then
        $this->assertTrue($occurred);
    }

    /**
     * @test
     */
    public function shouldClear(): void
    {
        // given
        $compile = $this->createMock(CompileError::class);
        $runtime = $this->createMock(RuntimeError::class);
        $hostError = new BothHostError($compile, $runtime);

        $compile->expects($this->once())->method('clear');
        $runtime->expects($this->once())->method('clear');

        // when
        $hostError->clear();
    }

    /**
     * @test
     */
    public function shouldGetSafeRegexpException(): void
    {
        // given
        $compile = $this->createMock(CompileError::class);
        $runtime = $this->createMock(RuntimeError::class);
        $hostError = new BothHostError($compile, $runtime);
        $expected = $this->createMock(PregException::class);

        $compile->expects($this->once())->method('getSafeRegexpException')
            ->willReturn($expected);

        // when
        $result = $hostError->getSafeRegexpException('method_name', '/foo/');

        // then
        $this->assertSame($expected, $result);
    }
}
