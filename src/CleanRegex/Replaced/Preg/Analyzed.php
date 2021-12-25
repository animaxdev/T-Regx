<?php
namespace TRegx\CleanRegex\Replaced\Preg;

use TRegx\CleanRegex\Internal\Definition;
use TRegx\CleanRegex\Internal\Subject;
use TRegx\SafeRegex\preg;

class Analyzed
{
    /** @var Definition */
    private $definition;
    /** @var Subject */
    private $subject;

    public function __construct(Definition $definition, Subject $subject)
    {
        $this->definition = $definition;
        $this->subject = $subject;
    }

    public function analyzedSubject(): array
    {
        preg::match_all($this->definition->pattern, $this->subject->getSubject(), $matches, \PREG_OFFSET_CAPTURE);
        return $matches;
    }
}
