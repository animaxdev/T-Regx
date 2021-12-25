<?php
namespace TRegx\CleanRegex\Replaced\Group;

use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Replaced\Preg\IndexedMatchAware;

class SequenceMatchAware
{
    /** @var IndexedMatchAware */
    private $matchAware;
    /** @var int */
    private $index;

    public function __construct(IndexedMatchAware $matchAware)
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
