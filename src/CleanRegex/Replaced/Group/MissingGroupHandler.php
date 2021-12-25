<?php
namespace TRegx\CleanRegex\Replaced\Group;

use TRegx\CleanRegex\Internal\GroupKey\GroupKey;

/**
 * Please, rename it to something more OOP
 * @deprecated
 */
interface MissingGroupHandler
{
    public function handle(GroupKey $group, string $original): string;
}
