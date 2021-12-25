<?php
namespace TRegx\CleanRegex\Replaced\Callback\Detail\Constituent;

use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\Model\GroupAware;

class LegacyModel
{
    /** @var string[] */
    private $match;

    public function __construct(array $match)
    {
        $this->match = $match;
    }

    public function potentiallyTrimmed(GroupAware $groupAware): bool
    {
        if ($groupAware->getGroupKeys() === \array_keys($this->match)) {
            return \in_array('', $this->match);
        }
        return true;
    }

    public function text(): string
    {
        return $this->match[0];
    }

    public function texts(): array
    {
        return $this->match;
    }

    public function isAwareOf(GroupKey $group): bool
    {
        return \array_key_exists($group->nameOrIndex(), $this->match);
    }

    public function groupText(GroupKey $group): string
    {
        return $this->match[$group->nameOrIndex()];
    }

    public function eitherMatchedOrEmpty(GroupKey $group): bool
    {
        /**
         * If the match contains empty string it can either
         * mean the group wasn't matched or it means the
         * group was matched with a string of length 0.
         */
        return $this->match[$group->nameOrIndex()] === '';
    }
}
