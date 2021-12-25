<?php
namespace TRegx\CleanRegex\Replaced\Callback\Detail\Constituent;

use TRegx\CleanRegex\Internal\Model\GroupAware;
use TRegx\CleanRegex\Internal\Model\Match\Entry;
use TRegx\CleanRegex\Internal\Model\Match\GroupEntries;
use TRegx\CleanRegex\Replaced\Callback\Detail\Group\Group;
use TRegx\CleanRegex\Replaced\Callback\Detail\Group\LegacyGroup;
use TRegx\CleanRegex\Replaced\Callback\Detail\MatchAware;
use TRegx\CleanRegex\Replaced\Preg\ByteOffset;

class LegacyConstituent implements Constituent
{
    /** @var string */
    private $text;
    /** @var LegacyGroup */
    private $group;
    /** @var LegacyEntries */
    private $compo;
    /** @var Entry */
    private $entry;

    public function __construct(GroupAware $groupAware, MatchAware $aware, LegacyModel $model, GroupEntries $composite, ByteOffset $byteOffset)
    {
        $this->text = $model->text();
        $this->group = new LegacyGroup($groupAware, $aware, $model);
        $this->compo = new LegacyEntries($groupAware, $model, $composite);
        $this->entry = new ReplaceEntry($this->text, $byteOffset);
    }

    public function text(): string
    {
        return $this->text;
    }

    public function compo(): GroupEntries
    {
        return $this->compo;
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
