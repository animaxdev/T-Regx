<?php
namespace TRegx\CleanRegex\Replaced\Callback\Detail\Constituent;

use TRegx\CleanRegex\Internal\Model\Match\Entry;
use TRegx\CleanRegex\Internal\Model\Match\GroupEntries;
use TRegx\CleanRegex\Replaced\Callback\Detail\Group\Group;

interface Constituent
{
    public function text(): string;

    public function entry(): Entry;

    public function group(): Group;

    public function groupEntries(): GroupEntries;
}
