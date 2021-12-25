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
    private $entries;

    public function __construct(GroupAware $groupAware, LegacyModel $model, GroupEntries $entries)
    {
        $this->groupAware = $groupAware;
        $this->model = $model;
        $this->entries = $entries;
    }

    public function groupTexts(): array
    {
        if ($this->model->potentiallyTrimmed($this->groupAware)) {
            return $this->entries->groupTexts();
        }
        return $this->model->texts();
    }

    public function groupOffsets(): array
    {
        return $this->entries->groupOffsets();
    }
}
