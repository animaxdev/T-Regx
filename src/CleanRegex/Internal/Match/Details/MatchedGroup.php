<?php
namespace TRegx\CleanRegex\Internal\Match\Details;

use TRegx\CleanRegex\Exception\NonexistentGroupException;
use TRegx\CleanRegex\Internal\GroupKey\GroupKey;

class MatchedGroup
{
    /** @var array */
    private $match;
    /** @var array */
    private $matches;

    public function __construct(array $match, array $matches)
    {
        $this->match = $match;
        $this->matches = $matches;
    }

    public function matched(GroupKey $group): bool
    {
        $nameOrIndex = $group->nameOrIndex();
        if (\array_key_exists($nameOrIndex, $this->match)) {
            if ($this->match[$nameOrIndex] !== '') {
                return true;
            }
        }
        if (\array_key_exists($nameOrIndex, $this->matches)) {
            return $this->matches[$nameOrIndex][1] > -1;
        }
        throw new NonexistentGroupException($group);
    }
}
