<?php
namespace TRegx\CleanRegex\Internal\Match\Details;

use TRegx\CleanRegex\Exception\GroupNotMatchedException;
use TRegx\CleanRegex\Exception\NonexistentGroupException;
use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\Model\GroupHasAware;

class MatchedGroup
{
    /** @var GroupHasAware */
    private $aware;
    /** @var array */
    private $match;
    /** @var array */
    private $matches;

    public function __construct(GroupHasAware $aware, array $match, array $matches)
    {
        $this->aware = $aware;
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
        } else {
            if (!$this->aware->hasGroup($nameOrIndex)) {
                throw new NonexistentGroupException($group);
            }
        }
        return $this->matches[$nameOrIndex][1] > -1;
    }

    public function get(GroupKey $group): string
    {
        $nameOrIndex = $group->nameOrIndex();
        if (\array_key_exists($nameOrIndex, $this->match)) {
            if ($this->match[$nameOrIndex] !== '') {
                return $this->match[$nameOrIndex];
            }
        } else {
            if (!$this->aware->hasGroup($nameOrIndex)) {
                throw new NonexistentGroupException($group);
            }
        }
        if ($this->matches[$nameOrIndex][1] === -1) {
            throw GroupNotMatchedException::forGet($group);
        }
        return '';
    }
}
