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
            $offset = $matches[0][$counter][1];
            $analyzed = $this->shifted($matches, $counter);
            return $function->apply(new ReplaceDetail($this->subject,
                $this->groupAware,
                $match,
                $counter++,
                $this->limit,
                $offset,
                $analyzed
            ));
        });
    }

    private function shifted(array $arrays, int $key): array
    {
        return \array_map(static function (array $array) use ($key): array {
            if ($array[$key] === '') {
                return [null, -1];
            }
            return $array[$key];
        }, $arrays);
    }

    private function analyzedSubject(): array
    {
        preg::match_all($this->definition->pattern, $this->subject->getSubject(), $matches, \PREG_OFFSET_CAPTURE);
        return $matches;
    }
}
