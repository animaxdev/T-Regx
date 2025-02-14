<?php
namespace TRegx\CleanRegex\Internal\Message\GroupNotMatched;

use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\Message\NotMatchedMessage;

class FromFirstMatchOffsetMessage implements NotMatchedMessage
{
    /** @var GroupKey */
    private $group;

    public function __construct(GroupKey $group)
    {
        $this->group = $group;
    }

    public function getMessage(): string
    {
        return "Expected to get group $this->group offset from the first match, but the group was not matched";
    }
}
