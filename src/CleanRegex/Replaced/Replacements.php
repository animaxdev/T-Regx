<?php
namespace TRegx\CleanRegex\Replaced;

use TRegx\CleanRegex\Exception\MissingReplacementKeyException;
use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\InvalidArgument;
use TRegx\CleanRegex\Internal\Message\NotMatchedMessage;
use TRegx\CleanRegex\Internal\Message\Replace\Map\ForGroupMessage;
use TRegx\CleanRegex\Internal\Message\Replace\Map\ForMatchMessage;
use TRegx\CleanRegex\Internal\Type\ValueType;

class Replacements
{
    /** @var string[] */
    private $replacements;

    public function __construct(array $replacements)
    {
        $this->replacements = $replacements;
        $this->validate($replacements);
    }

    public function replaced(GroupKey $group, string $occurrence, string $original): string
    {
        if (\array_key_exists($occurrence, $this->replacements)) {
            return $this->replacements[$occurrence];
        }
        throw new MissingReplacementKeyException($this->message($group, $original, $occurrence)->getMessage());
    }

    private function message(GroupKey $group, string $match, string $occurrence): NotMatchedMessage
    {
        if ($group->full()) {
            return new ForMatchMessage($match);
        }
        return new ForGroupMessage($match, $group, $occurrence);
    }

    private function validate(array $occurrencesAndReplacements): void
    {
        foreach ($occurrencesAndReplacements as $replacement) {
            if (!\is_string($replacement)) {
                throw InvalidArgument::typeGiven('Invalid replacement map value. Expected string', new ValueType($replacement));
            }
        }
    }
}
