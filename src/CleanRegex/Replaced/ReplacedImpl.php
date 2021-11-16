<?php
namespace TRegx\CleanRegex\Replaced;

use TRegx\CleanRegex\Internal\Definition;
use TRegx\CleanRegex\Internal\Subject;

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
        return new ReplaceOperationImpl($this->definition, $this->subject, -1);
    }

    public function first(): ReplaceOperation
    {
        return new ReplaceOperationImpl($this->definition, $this->subject, 1);
    }

    public function only(int $amount): ReplaceOperation
    {
        if ($amount < 0) {
            throw new \InvalidArgumentException("Negative limit: $amount");
        }
        return new ReplaceOperationImpl($this->definition, $this->subject, $amount);
    }

    public function exactly(): ReplaceExpectation
    {
        // TODO: Implement exactly() method.
    }

    public function atMost(): ReplaceExpectation
    {
        // TODO: Implement atMost() method.
    }

    public function atLeast(): ReplaceExpectation
    {
        // TODO: Implement atLeast() method.
    }

    public function counting(callable $consumer): ReplaceExpectation
    {
        // TODO: Implement counting() method.
    }

    public function byMap(array $occurrencesAndReplacements): string
    {
        // TODO: Implement byMap() method.
    }

    public function withGroupOr($nameOrIndex): GroupReplacement
    {
        // TODO: Implement withGroupOr() method.
    }

    public function byGroupMap($nameOrIndex, array $occurrencesAndReplacements): string
    {
        // TODO: Implement byGroupMap() method.
    }

    public function byGroupMapOr($nameOrIndex, array $occurrencesAndReplacements): GroupReplacement
    {
        // TODO: Implement byGroupMapOr() method.
    }

    public function focus($nameOrIndex): Basic
    {
        // TODO: Implement focus() method.
    }
}
