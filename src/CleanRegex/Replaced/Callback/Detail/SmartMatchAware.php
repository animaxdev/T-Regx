<?php
namespace TRegx\CleanRegex\Replaced\Callback\Detail;

use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Replaced\Preg\MatchAware;

class SmartMatchAware
{
    /** @var MatchAware */
    private $matchAware;
    /** @var int */
    private $index;

    public function __construct(MatchAware $aware, int $index)
    {
        $this->matchAware = $aware;
        $this->index = $index;
    }

    public function matched(GroupKey $group): bool
    {
        return $this->matchAware->matched($this->index, $group);
    }
}
