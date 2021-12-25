<?php
namespace TRegx\CleanRegex\Replaced\Group;

class IdentityOperation implements GroupOperation
{
    public function make(string $group, string $occurrence): string
    {
        return $group;
    }
}
