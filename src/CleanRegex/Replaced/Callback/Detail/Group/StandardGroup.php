<?php
namespace TRegx\CleanRegex\Replaced\Callback\Detail\Group;

use TRegx\CleanRegex\Exception\GroupNotMatchedException;
use TRegx\CleanRegex\Exception\NonexistentGroupException;
use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\Model\GroupHasAware;
use TRegx\CleanRegex\Replaced\Callback\Detail\Constituent\StandardModel;
use TRegx\CleanRegex\Replaced\Callback\Detail\SmartMatchAware;

class StandardGroup implements Group
{
    /** @var GroupHasAware */
    private $aware;
    /** @var StandardModel */
    private $model;
    /** @var SmartMatchAware */
    private $matchAware;

    public function __construct(GroupHasAware $aware, StandardModel $model, SmartMatchAware $matchAware)
    {
        $this->aware = $aware;
        $this->model = $model;
        $this->matchAware = $matchAware;
    }

    public function matched(GroupKey $group): bool
    {
        if ($this->model->isAwareOf($group)) {
            return $this->model->groupMatched($group);
        }
        if ($group->exists($this->aware)) {
            return $this->matchAware->matched($group);
        }
        throw new NonexistentGroupException($group);
    }

    public function get(GroupKey $group): string
    {
        if ($this->model->isAwareOf($group)) {
            return $this->matchedGroup($group);
        }
        if ($group->exists($this->aware)) {
            return $this->unmatchedGroup($group);
        }
        throw new NonexistentGroupException($group);
    }

    private function matchedGroup(GroupKey $group): string
    {
        if ($this->model->groupMatched($group)) {
            return $this->model->groupText($group);
        }
        throw GroupNotMatchedException::forGet($group);
    }

    private function unmatchedGroup(GroupKey $group): string
    {
        if ($this->matchAware->matched($group)) {
            return '';
        }
        throw GroupNotMatchedException::forGet($group);
    }
}
