<?php
namespace Test\Unit\TRegx\CleanRegex\Match\MatchPattern\for_each;

use PHPUnit\Framework\TestCase;
use Test\Utils\Definitions;
use Test\Utils\Functions;
use Test\Utils\TestCasePasses;
use TRegx\CleanRegex\Internal\StringSubject;
use TRegx\CleanRegex\Match\Details\Detail;
use TRegx\CleanRegex\Match\MatchPattern;

/**
 * @covers \TRegx\CleanRegex\Match\MatchPattern::forEach
 */
class MatchPatternTest extends TestCase
{
    use TestCasePasses;

    /**
     * @test
     */
    public function shouldGetMatch_withDetails()
    {
        // given
        $pattern = $this->getMatchPattern("Nice matching pattern");
        $counter = 0;
        $matches = ['Nice', 'matching', 'pattern'];

        // when
        $pattern->forEach(function (Detail $detail) use (&$counter, $matches) {
            // then
            $this->assertSame($matches[$counter], $detail->text());
            $this->assertSame($counter++, $detail->index());
            $this->assertSame('Nice matching pattern', $detail->subject());
            $this->assertSame(['Nice', 'matching', 'pattern'], $detail->all());
        });
    }

    /**
     * @test
     */
    public function shouldNotInvokeCallback_onNotMatchingSubject()
    {
        // given
        $pattern = $this->getMatchPattern('NOT MATCHING');

        // when
        $pattern->forEach(Functions::fail());

        // then
        $this->pass();
    }

    private function getMatchPattern(string $subject): MatchPattern
    {
        return new MatchPattern(Definitions::pattern("([A-Z])?[a-z']+"), new StringSubject($subject));
    }
}
