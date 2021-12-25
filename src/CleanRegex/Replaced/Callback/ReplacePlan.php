<?php
namespace TRegx\CleanRegex\Replaced\Callback;

use TRegx\CleanRegex\Internal\Definition;
use TRegx\CleanRegex\Internal\Subject;
use TRegx\CleanRegex\Replaced\Callback\Detail\Constituent\Constituents;
use TRegx\CleanRegex\Replaced\Callback\Detail\Constituent\Low;
use TRegx\CleanRegex\Replaced\Callback\Detail\DetailCallback;

class ReplacePlan
{
    /** @var Low */
    private $low;
    /** @var Constituents */
    private $constituents;

    public function __construct(Definition $definition, Subject $subject, int $limit, Constituents $constituents)
    {
        $this->low = new Low($definition, $subject, $limit);
        $this->constituents = $constituents;
    }

    public function replaced(DetailCallback $callback): string
    {
        return $this->low->replaced(new AbstractionCalled($this->constituents, $callback));
    }
}
