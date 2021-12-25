<?php
namespace TRegx\CleanRegex\Internal\Model\Match;

use TRegx\CleanRegex\Internal\Match\Stream\Base\MatchGroupIntStream;
use TRegx\CleanRegex\Internal\Match\Stream\Base\MatchGroupStream;
use TRegx\CleanRegex\Replaced\Callback\Detail\Group\Group;

interface UsedForGroup
{
    /**
     * @see MatchDetail::get
     * @see Group
     * @see MatchGroupIntStream::first()
     * @see MatchGroupIntStream::firstKey()
     * @see MatchGroupStream::all
     * @see GroupFacade which is called by everything that calls {@see getGroupTextAndOffset}
     */
    public function isGroupMatched($nameOrIndex): bool;

    /**
     * @see MatchDetail::get
     * @see Group
     * @see GroupLimit
     * @see GroupLimitFindFirst
     * @see DuplicateName::group
     * @see MatchDetail::group
     */
    public function getGroupTextAndOffset($nameOrIndex): array;
}
