<?php
namespace TRegx\CleanRegex\Replaced\ByMap;

use TRegx\CleanRegex\Internal\GroupKey\GroupKey;

interface MissingGroupHandler
{
    public function handle(GroupKey $group, string $original): string;
}
