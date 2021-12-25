<?php
namespace TRegx\CleanRegex\Internal\GroupKey;

use TRegx\CleanRegex\Exception\InternalCleanRegexException;
use TRegx\CleanRegex\Internal\Model\GroupHasAware;

class WholeMatch extends GroupKey
{
    public function nameOrIndex(): int
    {
        return 0;
    }

    public function full(): bool
    {
        return true;
    }

    public function exists(GroupHasAware $groupAware): bool
    {
        return true;
    }

    public function __toString(): string
    {
        // @codeCoverageIgnoreStart
        throw new InternalCleanRegexException();
        // @codeCoverageIgnoreEnd
    }
}
