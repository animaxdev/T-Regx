<?php
namespace TRegx\CleanRegex\Replaced;

use TRegx\CleanRegex\Internal\Definition;
use TRegx\CleanRegex\Internal\Subject;
use TRegx\SafeRegex\preg;

class OccurrencesAmount
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

    public function count(): int
    {
        preg::replace($this->definition->pattern, '', $this->subject->getSubject(), $this->limit + 1, $realCount);
        return $realCount;
    }
}
