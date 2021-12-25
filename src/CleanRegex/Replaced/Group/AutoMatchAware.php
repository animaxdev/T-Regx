<?php
namespace TRegx\CleanRegex\Replaced\Group;

use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Replaced\Preg\MatchAware;

class AutoMatchAware
{
    /** @var MatchAware */
    private $matchAware;
    /** @var int */
    private $index;

    public function __construct(MatchAware $matchAware)
    {
        $this->matchAware = $matchAware;
        $this->index = 0;
    }

    public function next(): void
    {
        ++$this->index;
    }

    public function matched(GroupKey $group): bool
    {
        return $this->matchAware->matched($this->index, $group);
    }
}
