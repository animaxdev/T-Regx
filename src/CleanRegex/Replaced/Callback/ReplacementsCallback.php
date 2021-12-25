<?php
namespace TRegx\CleanRegex\Replaced\Callback;

use TRegx\CleanRegex\Internal\Message\Replace\Map\ForMatchMessage;
use TRegx\CleanRegex\Replaced\Replacements;

class ReplacementsCallback implements TextCallback
{
    /** @var Replacements */
    private $replacements;

    public function __construct(Replacements $replacements)
    {
        $this->replacements = $replacements;
    }

    public function replace(string $text): string
    {
        return $this->replacements->replaced($text, new ForMatchMessage($text));
    }
}
