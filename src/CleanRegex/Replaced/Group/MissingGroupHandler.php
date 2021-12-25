<?php
namespace TRegx\CleanRegex\Replaced\Group;

use TRegx\CleanRegex\Internal\GroupKey\GroupKey;

interface MissingGroupHandler
{
    public function handle(GroupKey $group, string $original): string;
}
