<?php
namespace Test\Unit\TRegx\CleanRegex\Match\MatchPattern\flatMapAssoc;

use PHPUnit\Framework\TestCase;
use Test\Utils\Definitions;
use Test\Utils\Functions;
use TRegx\CleanRegex\Exception\InvalidReturnValueException;
use TRegx\CleanRegex\Internal\StringSubject;
use TRegx\CleanRegex\Match\MatchPattern;

/**
 * @covers \TRegx\CleanRegex\Match\MatchPattern::flatMapAssoc
 */
class MatchPatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldThrow_onNonArrayReturnType()
    {
        // given
        $pattern = $this->getMatchPattern('Nice 1 matching 2 pattern');

        // then
        $this->expectException(InvalidReturnValueException::class);
        $this->expectExceptionMessage("Invalid flatMapAssoc() callback return type. Expected array, but string ('string') given");

        // when
        $pattern->flatMapAssoc(Functions::constant('string'));
    }

    private function getMatchPattern(string $subject): MatchPattern
    {
        return new MatchPattern(Definitions::pattern("([A-Z])?[a-z']+"), new StringSubject($subject));
    }
}
