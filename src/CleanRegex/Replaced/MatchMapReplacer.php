<?php
namespace TRegx\CleanRegex\Replaced;

use TRegx\CleanRegex\Internal\Message\Replace\Map\ForMatchMessage;

class MatchMapReplacer
{
    /** @var CalledBack */
    private $calledBack;

    public function __construct(CalledBack $calledBack)
    {
        $this->calledBack = $calledBack;
    }

    public function replaced(Replacements $replacements): string
    {
        return $this->calledBack->replaced(static function (array $matches) use ($replacements): string {
            return $replacements->replaced($matches[0], new ForMatchMessage($matches[0]));
        });
    }
}
