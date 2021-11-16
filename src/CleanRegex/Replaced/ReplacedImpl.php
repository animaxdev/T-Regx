<?php
namespace TRegx\CleanRegex\Replaced;

use TRegx\CleanRegex\Exception\InvalidReplacementException;
use TRegx\CleanRegex\Internal\Definition;
use TRegx\CleanRegex\Internal\Subject;
use TRegx\CleanRegex\Internal\Type\ValueType;
use TRegx\SafeRegex\preg;

class ReplacedImpl implements Replaced
{
    /** @var Definition */
    private $definition;
    /** @var Subject */
    private $subject;
    /** @var ReplacerWith */
    private $replacerWith;

    public function __construct(Definition $definition, Subject $subject)
    {
        $this->definition = $definition;
        $this->subject = $subject;
        $this->replacerWith = new ReplacerWith($definition, $subject, -1);
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
        return preg::replace_callback($this->definition->pattern, function (array $matches) use ($replacer): string {
            $replacement = $replacer($matches[0]);
            if (\is_string($replacement)) {
                return $replacement;
            }
            throw new InvalidReplacementException(new ValueType($replacement));
        }, $this->subject->getSubject());
    }

    public function withGroup($nameOrIndex): string
    {
        // TODO: Implement withGroup() method.
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
