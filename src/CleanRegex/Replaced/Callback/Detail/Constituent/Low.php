<?php
namespace TRegx\CleanRegex\Replaced\Callback\Detail\Constituent;

use TRegx\CleanRegex\Internal\Definition;
use TRegx\CleanRegex\Internal\Subject;
use TRegx\CleanRegex\Replaced\Callback\AbstractionCalled;
use TRegx\SafeRegex\preg;

class Low
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

    public function replaced(AbstractionCalled $callback): string
    {
        $flags = $this->flags();
        return preg::replace_callback($this->definition->pattern, $this->callback($callback, $flags), $this->subject->getSubject(), $this->limit, $count, $flags);
    }

    private function callback(AbstractionCalled $callback, int $flags): \Closure
    {
        $index = 0;
        return static function (array $match) use ($callback, &$index, $flags): string {
            if ($flags === 0) {
                return $callback->replaceStrings(new LegacyModel($match), $index++);
            }
            return $callback->replaceOffsetArrays(new StandardModel($match), $index++);
        };
    }

    private function flags(): int
    {
        if (\defined('PREG_UNMATCHED_AS_NULL') && \defined('PREG_OFFSET_CAPTURE')) {
            return \PREG_UNMATCHED_AS_NULL | \PREG_OFFSET_CAPTURE;
        }
        return 0;
    }
}
