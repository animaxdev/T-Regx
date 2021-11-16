<?php
namespace TRegx\CleanRegex\Replaced;

use TRegx\CleanRegex\Exception\InvalidReplacementException;
use TRegx\CleanRegex\Internal\Definition;
use TRegx\CleanRegex\Internal\Subject;
use TRegx\CleanRegex\Internal\Type\ValueType;
use TRegx\SafeRegex\preg;

class ReplacerCallback
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

    public function replace(callable $replacer): string
    {
        return preg::replace_callback($this->definition->pattern, function (array $matches) use ($replacer): string {
            return $this->replacement($replacer($matches[0]));
        }, $this->subject->getSubject(), $this->limit);
    }

    public function replacement($replacement): string
    {
        if (\is_string($replacement)) {
            return $replacement;
        }
        throw new InvalidReplacementException(new ValueType($replacement));
    }
}
