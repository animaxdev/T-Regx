<?php
namespace TRegx\CleanRegex\Replaced;

class AtMostListenerFactory implements ListenerFactory
{
    /** @var OccurrencesAmount */
    private $amount;

    public function __construct(OccurrencesAmount $amount)
    {
        $this->amount = $amount;
    }

    public function create(int $limit): Listener
    {
        return new AtMostListener($this->amount, $limit);
    }
}
