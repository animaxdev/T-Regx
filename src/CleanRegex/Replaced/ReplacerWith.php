<?php
namespace TRegx\CleanRegex\Replaced;

use TRegx\CleanRegex\Internal\Definition;
use TRegx\CleanRegex\Internal\Replace\ReplaceReferences;
use TRegx\CleanRegex\Internal\Subject;
use TRegx\SafeRegex\preg;

class ReplacerWith
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

    public function with(string $replacement): string
    {
        return preg::replace($this->definition->pattern, ReplaceReferences::quote($replacement), $this->subject->getSubject(), $this->limit);
    }

    public function withReferences(string $replacement): string
    {
        return preg::replace($this->definition->pattern, $replacement, $this->subject->getSubject(), $this->limit);
    }
}
