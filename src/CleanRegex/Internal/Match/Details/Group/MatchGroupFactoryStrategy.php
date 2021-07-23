<?php
namespace TRegx\CleanRegex\Internal\Match\Details\Group;

use TRegx\CleanRegex\Internal\Factory\GroupExceptionFactory;
use TRegx\CleanRegex\Internal\Factory\Optional\NotMatchedOptionalWorker;
use TRegx\CleanRegex\Internal\Model\Match\MatchEntry;
use TRegx\CleanRegex\Match\Details\Group\MatchedGroup;
use TRegx\CleanRegex\Match\Details\Group\NotMatchedGroup;

class MatchGroupFactoryStrategy implements GroupFactoryStrategy
{
    public function createMatched(MatchEntry $match, GroupDetails $details, MatchedGroupOccurrence $matchedDetails): MatchedGroup
    {
        return new MatchedGroup($match, $details, $matchedDetails);
    }

    public function createUnmatched(GroupDetails $details,
                                    GroupExceptionFactory $exceptionFactory,
                                    NotMatchedOptionalWorker $optionalFactory,
                                    string $subject): NotMatchedGroup
    {
        return new NotMatchedGroup($details, $exceptionFactory, $optionalFactory, $subject);
    }
}
