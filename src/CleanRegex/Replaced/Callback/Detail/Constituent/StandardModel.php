<?php
namespace TRegx\CleanRegex\Replaced\Callback\Detail\Constituent;

use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\Model\Match\GroupEntries;

class StandardModel
{
    /** @var array */
    private $matchOffset;
    /** @var StandardEntries */
    private $entries;

    public function __construct(array $matchOffset)
    {
        $this->matchOffset = $matchOffset;
        $this->entries = new StandardEntries($matchOffset);
    }

    public function text(): string
    {
        return $this->matchOffset[0][0];
    }

    public function byteOffset(): int
    {
        return $this->matchOffset[0][1];
    }

    public function isAwareOf(GroupKey $group): bool
    {
        return \array_key_exists($group->nameOrIndex(), $this->matchOffset);
    }

    public function groupMatched(GroupKey $group): bool
    {
        return $this->matchOffset[$group->nameOrIndex()][1] > -1;
    }

    public function groupText(GroupKey $group): string
    {
        return $this->matchOffset[$group->nameOrIndex()][0];
    }

    public function groupEntries(): GroupEntries
    {
        return $this->entries;
    }
}
