<?php
namespace TRegx\CleanRegex\Replaced;

interface MapReplacer
{
    public function replaceGroup(Replacements $replacements, array $matches): string;
}
