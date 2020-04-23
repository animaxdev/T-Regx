<?php
namespace TRegx\CleanRegex\Internal\Match\GroupBy;

use TRegx\CleanRegex\Internal\Match\FlatMapper;
use TRegx\CleanRegex\Internal\Match\MatchAll\EagerMatchAllFactory;
use TRegx\CleanRegex\Internal\Model\Match\IndexedRawMatchOffset;
use TRegx\CleanRegex\Internal\Model\Matches\RawMatchesOffset;
use TRegx\CleanRegex\Internal\Model\MatchObjectFactory;

class FlatMapStrategy implements Strategy
{
    /** @var callable */
    private $mapper;
    /** @var MatchObjectFactory */
    private $factory;

    public function __construct(callable $mapper, MatchObjectFactory $factory)
    {
        $this->mapper = $mapper;
        $this->factory = $factory;
    }

    public function transform(array $groups, RawMatchesOffset $matches): array
    {
        foreach ($groups as &$group) {
            $group = (new FlatMapper($group, function (IndexedRawMatchOffset $match) use ($matches) {
                $mapper = $this->mapper;
                return $mapper($this->factory->create($match->getIndex(), $match, new EagerMatchAllFactory($matches)));
            }))->get();
        }
        return $groups;
    }
}
