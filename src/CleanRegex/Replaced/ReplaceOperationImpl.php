<?php
namespace TRegx\CleanRegex\Replaced;

use TRegx\CleanRegex\Internal\Definition;
use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\Model\LightweightGroupAware;
use TRegx\CleanRegex\Internal\Subject;
use TRegx\CleanRegex\Replaced\Callback\Detail\Constituent\Constituents;
use TRegx\CleanRegex\Replaced\Callback\ReplaceFunction;
use TRegx\CleanRegex\Replaced\Callback\ReplacementsCallback;
use TRegx\CleanRegex\Replaced\Callback\ReplacePlan;
use TRegx\CleanRegex\Replaced\Callback\ReplacerCallback;
use TRegx\CleanRegex\Replaced\Expectation\Listener;
use TRegx\CleanRegex\Replaced\Group\ConstantString;
use TRegx\CleanRegex\Replaced\Group\IdentityOperation;
use TRegx\CleanRegex\Replaced\Group\IgnoreHandler;
use TRegx\CleanRegex\Replaced\Group\MissingGroupHandler;
use TRegx\CleanRegex\Replaced\Group\ReplacementsGroupOperation;
use TRegx\CleanRegex\Replaced\Group\ReplacerWithGroup;
use TRegx\CleanRegex\Replaced\Group\SequenceMatchAware;
use TRegx\CleanRegex\Replaced\Group\ThrowHandler;
use TRegx\CleanRegex\Replaced\Preg\Analyzed;
use TRegx\CleanRegex\Replaced\Preg\Fetcher;
use TRegx\CleanRegex\Replaced\Preg\IndexedMatchAware;
use TRegx\CleanRegex\Replaced\Preg\Occurrences;
use TRegx\CleanRegex\Replaced\Preg\TextCalled;

class ReplaceOperationImpl implements ReplaceOperation
{
    /** @var ReplacerWith */
    private $replacerWith;
    /** @var ReplacerCallback */
    private $replacerCallback;
    /** @var ReplacerWithGroup */
    private $groupReplace;
    /** @var TextCalled */
    private $textCalled;

    public function __construct(Definition $definition, Subject $subject, int $limit, Listener $listener)
    {
        $groupAware = new LightweightGroupAware($definition);
        $analyzed = new Analyzed($definition, $subject);
        $matchAware = new IndexedMatchAware($analyzed);

        $this->replacerWith = new ReplacerWith($definition, $subject, $limit, $listener);
        $this->replacerCallback = new ReplacerCallback($groupAware, $subject, $limit, new Occurrences($analyzed),
            new ReplacePlan($definition, $subject, $limit, new Constituents($groupAware, $matchAware, new Fetcher($analyzed)), $listener));
        $this->groupReplace = new ReplacerWithGroup($definition, $subject, $limit, $groupAware, new SequenceMatchAware($matchAware), $listener);
        $this->textCalled = new TextCalled($definition, $subject, $limit, $listener);
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
        return $this->replacerCallback->replaced(new ReplaceFunction($replacer));
    }

    public function withGroup($nameOrIndex): string
    {
        return $this->replaceByGroup(GroupKey::of($nameOrIndex), new ThrowHandler());
    }

    public function withGroupOrIgnore($nameOrIndex): string
    {
        return $this->replaceByGroup(GroupKey::of($nameOrIndex), new IgnoreHandler());
    }

    public function withGroupOrEmpty($nameOrIndex): string
    {
        return $this->replaceByGroup(GroupKey::of($nameOrIndex), new ConstantString(''));
    }

    public function withGroupOrWith($nameOrIndex, string $substitute): string
    {
        return $this->replaceByGroup(GroupKey::of($nameOrIndex), new ConstantString($substitute));
    }

    private function replaceByGroup(GroupKey $group, MissingGroupHandler $handler): string
    {
        return $this->groupReplace->replaced($group, $handler, new IdentityOperation());
    }

    public function byMap(array $occurrencesAndReplacements): string
    {
        return $this->textCalled->replaced(new ReplacementsCallback(new Replacements($occurrencesAndReplacements)));
    }

    public function byGroupMap($nameOrIndex, array $occurrencesAndReplacements): string
    {
        return $this->replaceByGroupMap(GroupKey::of($nameOrIndex), new Replacements($occurrencesAndReplacements), new ThrowHandler());
    }

    public function byGroupMapOrIgnore($nameOrIndex, array $occurrencesAndReplacements): string
    {
        return $this->replaceByGroupMap(GroupKey::of($nameOrIndex), new Replacements($occurrencesAndReplacements), new IgnoreHandler());
    }

    public function byGroupMapOrEmpty($nameOrIndex, array $occurrencesAndReplacements): string
    {
        return $this->replaceByGroupMap(GroupKey::of($nameOrIndex), new Replacements($occurrencesAndReplacements), new ConstantString(''));
    }

    public function byGroupMapOrWith($nameOrIndex, array $occurrencesAndReplacements, string $substitute): string
    {
        return $this->replaceByGroupMap(GroupKey::of($nameOrIndex), new Replacements($occurrencesAndReplacements), new ConstantString($substitute));
    }

    private function replaceByGroupMap(GroupKey $group, Replacements $replacements, MissingGroupHandler $handler): string
    {
        return $this->groupReplace->replaced($group, $handler, new ReplacementsGroupOperation($replacements, $group));
    }
}
