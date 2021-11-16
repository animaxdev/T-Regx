<?php
namespace TRegx\CleanRegex\Replaced;

use TRegx\CleanRegex\Internal\Definition;
use TRegx\CleanRegex\Internal\Subject;

class ReplaceExpectationImpl implements ReplaceExpectation
{
    /** @var Definition */
    private $definition;
    /** @var Subject */
    private $subject;
    /** @var ListenerFactory */
    private $factory;

    public function __construct(Definition $definition, Subject $subject, ListenerFactory $factory)
    {
        $this->definition = $definition;
        $this->subject = $subject;
        $this->factory = $factory;
    }

    public function first(): ReplaceOperation
    {
        return new ReplaceOperationImpl($this->definition, $this->subject, 1, $this->factory->create(1));
    }

    public function only(int $amount): ReplaceOperation
    {
        return new ReplaceOperationImpl($this->definition, $this->subject, $amount, $this->factory->create($amount));
    }
}
