<?php
namespace TRegx\CleanRegex\Replaced\Callback\Detail\Constituent;

use TRegx\CleanRegex\Internal\Model\GroupAware;
use TRegx\CleanRegex\Internal\Model\Match\GroupEntries;

class LegacyEntries implements GroupEntries
{
    /** @var GroupAware */
    private $groupAware;
    /** @var LegacyModel */
    private $model;
    /** @var GroupEntries */
    private $groups;

    public function __construct(GroupAware $groupAware, LegacyModel $model, GroupEntries $groups)
    {
        $this->groupAware = $groupAware;
        $this->model = $model;
        $this->groups = $groups;
    }

    public function groupTexts(): array
    {
        if ($this->model->potentiallyTrimmed($this->groupAware)) {
            return $this->groups->groupTexts();
        }
        return $this->model->texts();
    }

    public function groupOffsets(): array
    {
        return $this->groups->groupOffsets();
    }
}
