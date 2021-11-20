<?php
namespace TRegx\CleanRegex\Replaced\ByMap;

use TRegx\CleanRegex\Exception\GroupNotMatchedException;
use TRegx\CleanRegex\Internal\GroupKey\GroupKey;

class ThrowHandler implements MissingGroupHandler
{
    public function handle(GroupKey $group, string $original): string
    {
        throw GroupNotMatchedException::forReplacement($group);
    }
}
