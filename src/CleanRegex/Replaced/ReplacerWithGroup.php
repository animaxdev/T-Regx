<?php
namespace TRegx\CleanRegex\Replaced;

use TRegx\CleanRegex\Exception\NonexistentGroupException;
use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\Model\GroupAware;
use TRegx\CleanRegex\Replaced\ByMap\MissingGroupHandler;

class ReplacerWithGroup
{
    /** @var CalledBack */
    private $calledBack;
    /** @var GroupAware */
    private $groupAware;

    public function __construct(CalledBack $calledBack, GroupAware $groupAware)
    {
        $this->calledBack = $calledBack;
        $this->groupAware = $groupAware;
    }

    public function replaced(GroupKey $group, MissingGroupHandler $handler): string
    {
        [$replaced, $count] = $this->calledBack->replacedAndCounted(function (array $matches) use ($group, $handler) {
            return $this->replaceWithGroup($matches, $group, $handler);
        });
        if ($count === 0 && !$this->groupExists($group)) {
            throw new NonexistentGroupException($group);
        }
        return $replaced;
    }

    private function replaceWithGroup(array $matches, GroupKey $group, MissingGroupHandler $handler): string
    {
        $nameOrIndex = $group->nameOrIndex();
        if (\array_key_exists($nameOrIndex, $matches)) {
            return $matches[$nameOrIndex];
        }
        if ($this->groupExists($group)) {
            return $handler->handle($group, $matches[0]);
        }
        throw new NonexistentGroupException($group);
    }

    private function groupExists(GroupKey $groupKey): bool
    {
        return $this->groupAware->hasGroup($groupKey->nameOrIndex());
    }
}
