<?php
namespace TRegx\CleanRegex\Replaced\Callback;

use TRegx\CleanRegex\Internal\Definition;
use TRegx\CleanRegex\Internal\Subject;
use TRegx\CleanRegex\Replaced\Callback\Detail\Constituent\Constituents;
use TRegx\CleanRegex\Replaced\Callback\Detail\Constituent\Low;
use TRegx\CleanRegex\Replaced\Callback\Detail\DetailCallback;
use TRegx\CleanRegex\Replaced\Expectation\Listener;

class ReplacePlan
{
    /** @var Low */
    private $low;
    /** @var Constituents */
    private $constituents;

    public function __construct(Definition $definition, Subject $subject, int $limit, Constituents $constituents, Listener $listener)
    {
        $this->low = new Low($definition, $subject, $limit, $listener);
        $this->constituents = $constituents;
    }

    public function replaced(DetailCallback $callback): string
    {
        return $this->low->replaced(new AbstractionCalled($this->constituents, $callback));
    }
}
