<?php
namespace TRegx\CleanRegex\Replaced\Callback\Detail\Group;

use TRegx\CleanRegex\Internal\GroupKey\GroupKey;

interface Group
{
    public function get(GroupKey $group): string;

    public function matched(GroupKey $group): bool;
}
