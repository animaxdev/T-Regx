<?php
namespace TRegx\CleanRegex\Replaced;

use TRegx\CleanRegex\Exception\InternalCleanRegexException;
use TRegx\CleanRegex\Internal\GroupKey\GroupKey;

class InternalThrowHandler implements MissingGroupHandler
{
    public function handle(GroupKey $group, string $original): string
    {
        // @codeCoverageIgnoreStart
        throw new InternalCleanRegexException();
        // @codeCoverageIgnoreEnd
    }
}
