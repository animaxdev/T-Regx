<?php
namespace TRegx\CleanRegex\Replaced;

use TRegx\CleanRegex\Exception\NonexistentGroupException;
use TRegx\CleanRegex\Internal\Definition;
use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\Subject;
use TRegx\SafeRegex\preg;

class ReplacedImpl implements Replaced
{
    /** @var Definition */
    private $definition;
    /** @var Subject */
    private $subject;
    /** @var ReplacerWith */
    private $replacerWith;
    /** @var ReplacerCallback */
    private $replacerCallback;

    public function __construct(Definition $definition, Subject $subject)
    {
        $this->definition = $definition;
        $this->subject = $subject;
        $this->replacerWith = new ReplacerWith($definition, $subject, -1);
        $this->replacerCallback = new ReplacerCallback($definition, $subject, -1);
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

    public function with(string $replacement): string
    {
        return $this->replacerWith->with($replacement);
    }

    public function withReferences(string $replacement): string
    {
        return $this->replacerWith->withReferences($replacement);
    }

    public function callback(callable $replacer): string
    {
        return $this->replacerCallback->replace($replacer);
    }

    public function withGroup($nameOrIndex): string
    {
        $groupKey = GroupKey::of($nameOrIndex);
        $result = preg::replace_callback($this->definition->pattern, function (array $matches) use ($groupKey, $nameOrIndex) {
            if (\array_key_exists($nameOrIndex, $matches)) {
                return $matches[$nameOrIndex];
            }
            throw new NonexistentGroupException($groupKey);
        }, $this->subject->getSubject(), -1, $count);
        if ($count === 0) {
            preg_match_all($this->definition->pattern, '', $matches);
            if (!\array_key_exists($nameOrIndex, $matches)) {
                throw new NonexistentGroupException($groupKey);
            }
        }
        return $result;
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
