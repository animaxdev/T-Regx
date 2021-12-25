<?php
namespace TRegx\CleanRegex\Replaced\Group;

use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\Message\Replace\Map\ForGroupMessage;
use TRegx\CleanRegex\Replaced\Replacements;

class ReplacementsGroupOperation implements GroupOperation
{
    /** @var Replacements */
    private $replacements;
    /** @var GroupKey */
    private $group;

    public function __construct(Replacements $replacements, GroupKey $group)
    {
        $this->replacements = $replacements;
        $this->group = $group;
    }

    public function make(string $group, string $occurrence): string
    {
        return $this->replacements->replaced($group, new ForGroupMessage($occurrence, $this->group, $group));
    }
}
