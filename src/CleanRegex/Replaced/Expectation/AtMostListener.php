<?php
namespace TRegx\CleanRegex\Replaced\Expectation;

use TRegx\CleanRegex\Exception\ReplacementExpectationFailedException;

class AtMostListener implements Listener
{
    /** @var OccurrencesAmount */
    private $amount;
    /** @var int */
    private $atMost;

    public function __construct(OccurrencesAmount $amount, int $atMost)
    {
        $this->amount = $amount;
        $this->atMost = $atMost;
    }

    public function replaced(int $replaced): void
    {
        $amount = $this->amount->count();
        if ($amount > $this->atMost) {
            throw ReplacementExpectationFailedException::superfluous($amount, $this->atMost, 'at most');
        }
    }
}
