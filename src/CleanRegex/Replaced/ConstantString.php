<?php
namespace TRegx\CleanRegex\Replaced;

use TRegx\CleanRegex\Internal\GroupKey\GroupKey;

class ConstantString implements MissingGroupHandler
{

    public function __construct()
    {
    }

    public function handle(GroupKey $group, string $original): string
    {
        return '';
    }
}
