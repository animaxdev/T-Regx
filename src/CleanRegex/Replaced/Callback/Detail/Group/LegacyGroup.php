<?php
namespace TRegx\CleanRegex\Replaced\Callback\Detail\Group;

use TRegx\CleanRegex\Exception\GroupNotMatchedException;
use TRegx\CleanRegex\Exception\NonexistentGroupException;
use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\Model\GroupHasAware;
use TRegx\CleanRegex\Replaced\Callback\Detail\Constituent\LegacyModel;
use TRegx\CleanRegex\Replaced\Callback\Detail\MatchAware;

class LegacyGroup implements Group
{
    /** @var GroupHasAware */
    private $aware;
    /** @var MatchAware */
    private $matchAware;
    /** @var LegacyModel */
    private $model;

    public function __construct(GroupHasAware $aware, MatchAware $matchAware, LegacyModel $model)
    {
        $this->aware = $aware;
        $this->matchAware = $matchAware;
        $this->model = $model;
    }

    public function matched(GroupKey $group): bool
    {
        if ($this->model->isAwareOf($group)) {
            return $this->isAwareGroupMatched($group);
        }
        if ($group->exists($this->aware)) {
            return $this->matchAware->matched($group);
        }
        throw new NonexistentGroupException($group);
    }

    private function isAwareGroupMatched(GroupKey $group): bool
    {
        if ($this->model->eitherMatchedOrEmpty($group)) {
            return $this->matchAware->matched($group);
        }
        return true;
    }

    public function get(GroupKey $group): string
    {
        if ($this->model->isAwareOf($group)) {
            return $this->matchedAwareGroup($group);
        }
        return $this->matchedUnawareGroup($group);
    }

    private function matchedAwareGroup(GroupKey $group): string
    {
        if ($this->model->eitherMatchedOrEmpty($group)) {
            return $this->matchedGroup($group);
        }
        return $this->model->groupText($group);
    }

    private function matchedUnawareGroup(GroupKey $group): string
    {
        if ($group->exists($this->aware)) {
            return $this->matchedGroup($group);
        }
        throw new NonexistentGroupException($group);
    }

    private function matchedGroup(GroupKey $group): string
    {
        if ($this->matchAware->matched($group)) {
            return '';
        }
        throw GroupNotMatchedException::forGet($group);
    }
}
