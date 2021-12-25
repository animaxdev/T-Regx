<?php
namespace TRegx\CleanRegex\Replaced\Group;

use TRegx\CleanRegex\Internal\GroupKey\GroupKey;

class ConstantString implements MissingGroupHandler
{
    /** @var string */
    private $string;

    public function __construct(string $string)
    {
        $this->string = $string;
    }

    public function handle(GroupKey $group, string $original): string
    {
        return $this->string;
    }
}
