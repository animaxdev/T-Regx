<?php
namespace Test\Fakes\CleanRegex\Replaced\OccurrencesAmount;

use Test\Fakes\CleanRegex\Internal\ThrowSubject;
use Test\Utils\Fails;
use TRegx\CleanRegex\Internal\Definition;
use TRegx\CleanRegex\Replaced\Expectation\OccurrencesAmount;

class ThrowOccurrences extends OccurrencesAmount
{
    use Fails;

    public function __construct()
    {
        parent::__construct(new Definition('', ''), new ThrowSubject(), -1);
    }

    public function count(): int
    {
        throw $this->fail();
    }
}
