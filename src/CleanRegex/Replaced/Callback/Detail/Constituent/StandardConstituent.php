<?php
namespace TRegx\CleanRegex\Replaced\Callback\Detail\Constituent;

use TRegx\CleanRegex\Internal\Model\GroupHasAware;
use TRegx\CleanRegex\Internal\Model\Match\Entry;
use TRegx\CleanRegex\Internal\Model\Match\GroupEntries;
use TRegx\CleanRegex\Replaced\Callback\Detail\Group\Group;
use TRegx\CleanRegex\Replaced\Callback\Detail\Group\StandardGroup;
use TRegx\CleanRegex\Replaced\Callback\Detail\SmartMatchAware;
use TRegx\CleanRegex\Replaced\ConstantEntry;

class StandardConstituent implements Constituent
{
    /** @var string */
    private $text;
    /** @var StandardEntries */
    private $entries;
    /** @var Group */
    private $group;
    /** @var Entry */
    private $entry;

    public function __construct(GroupHasAware $groupAware, SmartMatchAware $matchAware, StandardModel $model)
    {
        $this->text = $model->text();
        $this->entries = $model->groupEntries();
        $this->group = new StandardGroup($groupAware, $model, $matchAware);
        $this->entry = new ConstantEntry($this->text, $model->byteOffset());
    }

    public function text(): string
    {
        return $this->text;
    }

    public function compo(): GroupEntries
    {
        return $this->entries;
    }

    public function group(): Group
    {
        return $this->group;
    }

    public function entry(): Entry
    {
        return $this->entry;
    }
}
