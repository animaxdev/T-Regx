<?php
namespace TRegx\CleanRegex\Replaced;

use TRegx\CleanRegex\Internal\Definition;
use TRegx\CleanRegex\Internal\Subject;
use TRegx\CleanRegex\Replaced\Expectation\AtLeastListenerType;
use TRegx\CleanRegex\Replaced\Expectation\AtMostListenerType;
use TRegx\CleanRegex\Replaced\Expectation\ExactListenerType;
use TRegx\CleanRegex\Replaced\Expectation\IgnoreListener;

class ReplacedImpl implements Replaced
{
    use ReplaceLimitHelpers;

    /** @var Definition */
    private $definition;
    /** @var Subject */
    private $subject;

    public function __construct(Definition $definition, Subject $subject)
    {
        $this->definition = $definition;
        $this->subject = $subject;
    }

    public function all(): ReplaceOperation
    {
        return new ReplaceOperationImpl($this->definition, $this->subject, -1, new IgnoreListener());
    }

    public function first(): ReplaceOperation
    {
        return new ReplaceOperationImpl($this->definition, $this->subject, 1, new IgnoreListener());
    }

    public function only(int $amount): ReplaceOperation
    {
        if ($amount < 0) {
            throw new \InvalidArgumentException("Negative limit: $amount");
        }
        return new ReplaceOperationImpl($this->definition, $this->subject, $amount, new IgnoreListener());
    }

    public function exactly(): ReplaceExpectation
    {
        return new ReplaceExpectationImpl($this->definition, $this->subject, new ExactListenerType($this->definition, $this->subject));
    }

    public function atMost(): ReplaceExpectation
    {
        return new ReplaceExpectationImpl($this->definition, $this->subject, new AtMostListenerType($this->definition, $this->subject));
    }

    public function atLeast(): ReplaceExpectation
    {
        return new ReplaceExpectationImpl($this->definition, $this->subject, new AtLeastListenerType());
    }

    public function focus($nameOrIndex): Basic
    {
        // TODO: Implement focus() method.
    }
}
