<?php
namespace TRegx\CleanRegex\Replaced\Expectation;

use TRegx\CleanRegex\Exception\ReplacementExpectationFailedException;

class ExactListener implements Listener
{
    /** @var OccurrencesAmount */
    private $amount;
    /** @var int */
    private $exactly;

    public function __construct(OccurrencesAmount $amount, int $exactly)
    {
        $this->amount = $amount;
        $this->exactly = $exactly;
    }

    public function replaced(int $replaced): void
    {
        if ($replaced < $this->exactly) {
            throw ReplacementExpectationFailedException::insufficient($replaced, $this->exactly, 'exactly');
        }
        $amount = $this->amount->count();
        if ($amount > $this->exactly) {
            throw ReplacementExpectationFailedException::superfluous($amount, $this->exactly, 'exactly');
        }
    }
}
