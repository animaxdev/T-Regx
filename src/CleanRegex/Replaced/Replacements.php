<?php
namespace TRegx\CleanRegex\Replaced;

use TRegx\CleanRegex\Exception\MissingReplacementKeyException;
use TRegx\CleanRegex\Internal\InvalidArgument;
use TRegx\CleanRegex\Internal\Message\NotMatchedMessage;
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

    public function replaced(string $occurrence, NotMatchedMessage $message): string
    {
        if (\array_key_exists($occurrence, $this->replacements)) {
            return $this->replacements[$occurrence];
        }
        throw new MissingReplacementKeyException($message->getMessage());
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
