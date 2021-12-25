<?php
namespace TRegx\CleanRegex\Replaced\Callback\Detail;

use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Replaced\Preg\IndexedMatchAware;

class MatchAware
{
    /** @var IndexedMatchAware */
    private $aware;
    /** @var int */
    private $index;

    public function __construct(IndexedMatchAware $aware, int $index)
    {
        $this->aware = $aware;
        $this->index = $index;
    }

    public function matched(GroupKey $group): bool
    {
        return $this->aware->matched($this->index, $group);
    }
}
