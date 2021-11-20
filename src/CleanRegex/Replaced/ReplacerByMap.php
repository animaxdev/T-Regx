<?php
namespace TRegx\CleanRegex\Replaced;

use TRegx\CleanRegex\Internal\Definition;
use TRegx\CleanRegex\Internal\Subject;
use TRegx\SafeRegex\preg;

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

    public function replaced(Replacements $replacements, MapReplacer $mapReplacer): string
    {
        return preg::replace_callback($this->definition->pattern,
            function (array $matches) use ($mapReplacer, $replacements): string {
                return $mapReplacer->replaceGroup($replacements, $matches);
            },
            $this->subject->getSubject(),
            $this->limit);
    }
}
