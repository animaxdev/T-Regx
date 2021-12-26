<?php
namespace TRegx\CleanRegex\Replaced;

use TRegx\CleanRegex\Internal\Definition;
use TRegx\CleanRegex\Internal\Subject;
use TRegx\CleanRegex\Replaced\Expectation\ListenerType;

class ReplaceExpectationImpl implements ReplaceExpectation
{
    /** @var Definition */
    private $definition;
    /** @var Subject */
    private $subject;
    /** @var ListenerType */
    private $type;

    public function __construct(Definition $definition, Subject $subject, ListenerType $type)
    {
        $this->definition = $definition;
        $this->subject = $subject;
        $this->type = $type;
    }

    public function first(): ReplaceOperation
    {
        return new ReplaceOperationImpl($this->definition, $this->subject, 1, $this->type->listener(1));
    }

    public function only(int $amount): ReplaceOperation
    {
        return new ReplaceOperationImpl($this->definition, $this->subject, $amount, $this->type->listener($amount));
    }
}
