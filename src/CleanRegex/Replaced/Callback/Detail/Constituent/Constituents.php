<?php
namespace TRegx\CleanRegex\Replaced\Callback\Detail\Constituent;

use TRegx\CleanRegex\Internal\Model\GroupAware;
use TRegx\CleanRegex\Replaced\Callback\Detail\MatchAware;
use TRegx\CleanRegex\Replaced\Preg\Fetcher;
use TRegx\CleanRegex\Replaced\Preg\IndexedMatchAware;

class Constituents
{
    /** @var GroupAware */
    private $groupAware;
    /** @var IndexedMatchAware */
    private $matchAware;
    /** @var Fetcher */
    private $fetcher;

    public function __construct(GroupAware $groupAware, IndexedMatchAware $matchAware, Fetcher $fetcher)
    {
        $this->groupAware = $groupAware;
        $this->matchAware = $matchAware;
        $this->fetcher = $fetcher;
    }

    public function callbackOffset(StandardModel $model, int $index): Constituent
    {
        return new StandardConstituent($this->groupAware,
            new MatchAware($this->matchAware, $index),
            $model);
    }

    public function callbackString(LegacyModel $model, int $index): Constituent
    {
        return new LegacyConstituent($this->groupAware,
            new MatchAware($this->matchAware, $index),
            $model,
            $this->fetcher->groupEntries($index),
            $this->fetcher->byteOffset($index));
    }
}
