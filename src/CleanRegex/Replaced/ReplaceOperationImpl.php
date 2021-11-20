<?php
namespace TRegx\CleanRegex\Replaced;

use TRegx\CleanRegex\Internal\Definition;
use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\Model\GroupAware;
use TRegx\CleanRegex\Internal\Model\LightweightGroupAware;
use TRegx\CleanRegex\Internal\Subject;

class ReplaceOperationImpl implements ReplaceOperation
{
    /** @var ReplacerWith */
    private $replacerWith;
    /** @var ReplacerCallback */
    private $replacerCallback;
    /** @var ReplacerWithGroup */
    private $replacerWithGroup;
    /** @var ReplacerByMap */
    private $replacerByMap;
    /** @var GroupAware */
    private $groupAware;

    public function __construct(Definition $definition, Subject $subject, int $limit, Listener $listener)
    {
        $this->replacerWith = new ReplacerWith($definition, $subject, $limit, $listener);
        $this->replacerCallback = new ReplacerCallback($definition, $subject, $limit);
        $this->replacerWithGroup = new ReplacerWithGroup($definition, $subject, $limit);
        $this->replacerByMap = new ReplacerByMap($definition, $subject, $limit);
        $this->groupAware = new LightweightGroupAware($definition);
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
        return $this->replacerWithGroup->replace($nameOrIndex);
    }

    public function byMap(array $occurrencesAndReplacements): string
    {
        return $this->replacerByMap->replaced(new Replacements($occurrencesAndReplacements), new MatchMapReplacer());
    }

    public function withGroupOr($nameOrIndex): GroupReplacement
    {
        // TODO: Implement withGroupOr() method.
    }

    public function byGroupMap($nameOrIndex, array $occurrencesAndReplacements): string
    {
        return $this->replacerByMap->replaced(new Replacements($occurrencesAndReplacements), new GroupMapReplacer($this->groupAware, GroupKey::of($nameOrIndex), new ThrowHandler()));
    }

    public function byGroupMapOrIgnore($nameOrIndex, array $occurrencesAndReplacements): string
    {
        return $this->replacerByMap->replaced(new Replacements($occurrencesAndReplacements), new GroupMapReplacer($this->groupAware, GroupKey::of($nameOrIndex), new IgnoreHandler()));
    }

    public function byGroupMapOr($nameOrIndex, array $occurrencesAndReplacements): GroupReplacement
    {
        // TODO: Implement byGroupMapOr() method.
    }
}
