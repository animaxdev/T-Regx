<?php
namespace TRegx\CleanRegex\Replaced\Callback\Detail\Constituent;

use TRegx\CleanRegex\Internal\Model\GroupAware;
use TRegx\CleanRegex\Replaced\Callback\Detail\SmartMatchAware;
use TRegx\CleanRegex\Replaced\Preg\Fetcher;
use TRegx\CleanRegex\Replaced\Preg\MatchAware;

class Constituents
{
    /** @var GroupAware */
    private $groupAware;
    /** @var MatchAware */
    private $matchAware;
    /** @var Fetcher */
    private $fetcher;

    public function __construct(GroupAware $groupAware, MatchAware $matchAware, Fetcher $fetcher)
    {
        $this->groupAware = $groupAware;
        $this->matchAware = $matchAware;
        $this->fetcher = $fetcher;
    }

    public function callbackOffset(StandardModel $model, int $index): Constituent
    {
        return new StandardConstituent($this->groupAware,
            new SmartMatchAware($this->matchAware, $index),
            $model);
    }

    public function callbackString(LegacyModel $model, int $index): Constituent
    {
        return new LegacyConstituent($this->groupAware,
            new SmartMatchAware($this->matchAware, $index),
            $model,
            $this->fetcher->groupEntries($index),
            $this->fetcher->byteOffset($index));
    }
}
