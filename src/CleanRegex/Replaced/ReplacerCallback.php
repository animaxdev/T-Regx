<?php
namespace TRegx\CleanRegex\Replaced;

use TRegx\CleanRegex\Internal\Definition;
use TRegx\CleanRegex\Internal\Model\GroupAware;
use TRegx\CleanRegex\Internal\Subject;
use TRegx\SafeRegex\preg;

class ReplacerCallback
{
    /** @var CalledBack */
    private $calledBack;
    /** @var GroupAware */
    private $groupAware;
    /** @var Definition */
    private $definition;
    /** @var Subject */
    private $subject;
    /** @var int */
    private $limit;

    public function __construct(CalledBack $calledBack, GroupAware $groupAware, Definition $definition, Subject $subject, int $limit)
    {
        $this->calledBack = $calledBack;
        $this->groupAware = $groupAware;
        $this->subject = $subject;
        $this->limit = $limit;
        $this->definition = $definition;
    }

    public function replaced(ReplacementFunction $function): string
    {
        $counter = 0;
        $matches = $this->analyzedSubject();
        return $this->calledBack->replaced(function (array $match) use ($matches, $function, &$counter): string {
            [$text, $offset] = $matches[$counter];
            return $function->apply(new ReplaceDetail($this->subject,
                $this->groupAware,
                $match,
                $counter++,
                $this->limit,
                $offset));
        });
    }

    private function analyzedSubject(): array
    {
        preg::match_all($this->definition->pattern, $this->subject->getSubject(), $matches, \PREG_OFFSET_CAPTURE);
        return $matches[0];
    }
}
