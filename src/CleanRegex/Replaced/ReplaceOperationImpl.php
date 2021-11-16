<?php
namespace TRegx\CleanRegex\Replaced;

use TRegx\CleanRegex\Internal\Definition;
use TRegx\CleanRegex\Internal\Subject;

class ReplaceOperationImpl implements ReplaceOperation
{
    /** @var Definition */
    private $definition;
    /** @var Subject */
    private $subject;
    /** @var ReplacerWith */
    private $replacerWith;

    public function __construct(Definition $definition, Subject $subject, int $limit)
    {
        $this->definition = $definition;
        $this->subject = $subject;
        $this->replacerWith = new ReplacerWith($this->definition, $this->subject, $limit);
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
        // TODO: Implement callback() method.
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
}
