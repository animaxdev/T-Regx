<?php
namespace TRegx\CleanRegex\Replaced;

use TRegx\CleanRegex\Internal\Definition;
use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\Model\GroupAware;
use TRegx\CleanRegex\Internal\Model\LightweightGroupAware;
use TRegx\CleanRegex\Internal\Subject;
use TRegx\CleanRegex\Replaced\ByMap\ConstantString;
use TRegx\CleanRegex\Replaced\ByMap\GroupMapReplacer;
use TRegx\CleanRegex\Replaced\ByMap\IgnoreHandler;
use TRegx\CleanRegex\Replaced\ByMap\MatchMapReplacer;
use TRegx\CleanRegex\Replaced\ByMap\MissingGroupHandler;
use TRegx\CleanRegex\Replaced\ByMap\Replacements;
use TRegx\CleanRegex\Replaced\ByMap\ThrowHandler;

class ReplaceOperationImpl implements ReplaceOperation
{
    /** @var ReplacerWith */
    private $replacerWith;
    /** @var ReplacerCallback */
    private $replacerCallback;
    /** @var ReplacerWithGroup */
    private $replacerWithGroup;
    /** @var GroupAware */
    private $groupAware;
    /** @var CalledBack */
    private $calledBack;
    /** @var MatchMapReplacer */
    private $matchMapReplacer;

    public function __construct(Definition $definition, Subject $subject, int $limit, Listener $listener)
    {
        $this->calledBack = new CalledBack($definition, $subject, $limit);
        $this->replacerWith = new ReplacerWith($definition, $subject, $limit, $listener);
        $this->groupAware = new LightweightGroupAware($definition);
        $this->replacerCallback = new ReplacerCallback($this->calledBack, $this->groupAware, $definition, $subject, $limit);
        $this->replacerWithGroup = new ReplacerWithGroup($this->calledBack, $this->groupAware);
        $this->matchMapReplacer = new MatchMapReplacer($this->calledBack);
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
        return $this->replacerCallback->replaced(new ReplacementFunction($replacer));
    }

    public function withGroup($nameOrIndex): string
    {
        return $this->replacerWithGroup->replaced(GroupKey::of($nameOrIndex), (new ThrowHandler()));
    }

    public function withGroupOrIgnore($nameOrIndex): string
    {
        return $this->replacerWithGroup->replaced(GroupKey::of($nameOrIndex), (new IgnoreHandler()));
    }

    public function withGroupOrEmpty($nameOrIndex): string
    {
        return $this->replacerWithGroup->replaced(GroupKey::of($nameOrIndex), (new ConstantString('')));
    }

    public function withGroupOrWith($nameOrIndex, string $substitute): string
    {
        return $this->replacerWithGroup->replaced(GroupKey::of($nameOrIndex), (new ConstantString($substitute)));
    }

    public function byMap(array $occurrencesAndReplacements): string
    {
        return $this->matchMapReplacer->replaced(new Replacements($occurrencesAndReplacements));
    }

    public function withGroupOr($nameOrIndex): GroupReplacement
    {
        // TODO: Implement withGroupOr() method.
    }

    public function byGroupMap($nameOrIndex, array $occurrencesAndReplacements): string
    {
        return $this->byGroupMapOrHandled(GroupKey::of($nameOrIndex), new Replacements($occurrencesAndReplacements), new ThrowHandler());
    }

    public function byGroupMapOrIgnore($nameOrIndex, array $occurrencesAndReplacements): string
    {
        return $this->byGroupMapOrHandled(GroupKey::of($nameOrIndex), new Replacements($occurrencesAndReplacements), new IgnoreHandler());
    }

    public function byGroupMapOrEmpty($nameOrIndex, array $occurrencesAndReplacements): string
    {
        return $this->byGroupMapOrHandled(GroupKey::of($nameOrIndex), new Replacements($occurrencesAndReplacements), new ConstantString(''));
    }

    public function byGroupMapOrWith($nameOrIndex, array $occurrencesAndReplacements, string $substitute): string
    {
        return $this->byGroupMapOrHandled(GroupKey::of($nameOrIndex), new Replacements($occurrencesAndReplacements), new ConstantString($substitute));
    }

    private function byGroupMapOrHandled(GroupKey $group, Replacements $replacements, MissingGroupHandler $handler): string
    {
        return (new GroupMapReplacer($this->calledBack, $this->groupAware, $handler, $group))->replaced($replacements);
    }

    public function byGroupMapOr($nameOrIndex, array $occurrencesAndReplacements): GroupReplacement
    {
        // TODO: Implement byGroupMapOr() method.
    }
}
