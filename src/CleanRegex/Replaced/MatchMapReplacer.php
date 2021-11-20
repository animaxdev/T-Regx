<?php
namespace TRegx\CleanRegex\Replaced;

use TRegx\CleanRegex\Internal\Message\Replace\Map\ForMatchMessage;

class MatchMapReplacer implements MapReplacer
{
    public function replaceGroup(Replacements $replacements, array $matches): string
    {
        return $replacements->replaced($matches[0], new ForMatchMessage($matches[0]));
    }
}
