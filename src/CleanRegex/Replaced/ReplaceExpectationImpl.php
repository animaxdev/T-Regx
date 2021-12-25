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
    private $factory;

    public function __construct(Definition $definition, Subject $subject, ListenerType $factory)
    {
        $this->definition = $definition;
        $this->subject = $subject;
        $this->factory = $factory;
    }

    public function first(): ReplaceOperation
    {
        return new ReplaceOperationImpl($this->definition, $this->subject, 1, $this->factory->listener(1));
    }

    public function only(int $amount): ReplaceOperation
    {
        return new ReplaceOperationImpl($this->definition, $this->subject, $amount, $this->factory->listener($amount));
    }
}
