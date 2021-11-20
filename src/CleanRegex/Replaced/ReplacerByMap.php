<?php
namespace TRegx\CleanRegex\Replaced;

use TRegx\CleanRegex\Exception\MissingReplacementKeyException;
use TRegx\CleanRegex\Internal\Definition;
use TRegx\CleanRegex\Internal\InvalidArgument;
use TRegx\CleanRegex\Internal\Subject;
use TRegx\CleanRegex\Internal\Type\ValueType;

class ReplacerByMap
{
    /** @var Definition */
    private $definition;
    /** @var Subject */
    private $subject;
    /** @var int */
    private $limit;

    public function __construct(Definition $definition, Subject $subject, int $limit)
    {
        $this->definition = $definition;
        $this->subject = $subject;
        $this->limit = $limit;
    }

    public function byMap(array $replacements): string
    {
        $this->validate($replacements);
        return preg_replace_callback($this->definition->pattern, function (array $matches) use ($replacements): string {
            return $this->replacement($matches[0], $replacements);
        }, $this->subject->getSubject(), $this->limit);
    }

    private function replacement(string $occurrence, array $replacements): string
    {
        if (\array_key_exists($occurrence, $replacements)) {
            return $replacements[$occurrence];
        }
        throw new MissingReplacementKeyException("Expected to replace value '$occurrence', but such key is not found in replacement map");
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
