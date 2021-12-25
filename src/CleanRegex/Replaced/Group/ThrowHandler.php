<?php
namespace TRegx\CleanRegex\Replaced\Group;

use TRegx\CleanRegex\Exception\GroupNotMatchedException;
use TRegx\CleanRegex\Internal\GroupKey\GroupKey;

class ThrowHandler implements MissingGroupHandler
{
    public function handle(GroupKey $group, string $original): string
    {
        throw GroupNotMatchedException::forReplacement($group);
    }
}
