<?php
namespace TRegx\CleanRegex\Replaced;

use TRegx\CleanRegex\Internal\GroupKey\GroupKey;

class IgnoreHandler implements MissingGroupHandler
{
    public function handle(GroupKey $group, string $original): string
    {
        return $original;
    }
}
