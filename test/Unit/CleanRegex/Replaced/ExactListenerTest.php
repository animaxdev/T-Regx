<?php
namespace Test\Unit\TRegx\CleanRegex\Replaced;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Exception\ReplacementExpectationFailedException;
use TRegx\CleanRegex\Replaced\Expectation\ExactListener;

class ExactListenerTest extends TestCase
{
    /**
     * @test
     */
    public function shouldNotUnnecessarilyCountOccurrences()
    {
        // given
        $listener = new ExactListener(new \Test\Fakes\CleanRegex\Replaced\OccurrencesAmount\ThrowOccurrences(), 4);

        // then
        $this->expectException(ReplacementExpectationFailedException::class);

        // when
        $listener->replaced(3);
    }
}
