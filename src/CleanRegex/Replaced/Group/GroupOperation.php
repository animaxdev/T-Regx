<?php
namespace TRegx\CleanRegex\Replaced\Group;

interface GroupOperation
{
    public function make(string $group, string $occurrence): string;
}
