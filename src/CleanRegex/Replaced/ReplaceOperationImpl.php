<?php
namespace TRegx\CleanRegex\Replaced;

use TRegx\CleanRegex\Exception\NonexistentGroupException;
use TRegx\CleanRegex\Internal\Definition;
use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\Subject;
use TRegx\SafeRegex\preg;

class ReplaceOperationImpl implements ReplaceOperation
{
    /** @var Definition */
    private $definition;
    /** @var Subject */
    private $subject;
    /** @var ReplacerWith */
    private $replacerWith;
    /** @var ReplacerCallback */
    private $replacerCallback;

    public function __construct(Definition $definition, Subject $subject, int $limit)
    {
        $this->definition = $definition;
        $this->subject = $subject;
        $this->replacerWith = new ReplacerWith($this->definition, $this->subject, $limit);
        $this->replacerCallback = new ReplacerCallback($definition, $subject, $limit);
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
}
