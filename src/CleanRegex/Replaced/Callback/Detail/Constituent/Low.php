<?php
namespace TRegx\CleanRegex\Replaced\Callback\Detail\Constituent;

use TRegx\CleanRegex\Internal\Definition;
use TRegx\CleanRegex\Internal\Subject;
use TRegx\CleanRegex\Replaced\Callback\AbstractionCalled;
use TRegx\CleanRegex\Replaced\Expectation\Listener;
use TRegx\SafeRegex\preg;

class Low
{
    /** @var Definition */
    private $definition;
    /** @var Subject */
    private $subject;
    /** @var int */
    private $limit;
    /** @var Listener */
    private $listener;

    public function __construct(Definition $definition, Subject $subject, int $limit, Listener $listener)
    {
        $this->definition = $definition;
        $this->subject = $subject;
        $this->limit = $limit;
        $this->listener = $listener;
    }

    public function replaced(AbstractionCalled $callback): string
    {
        $flags = $this->flags();
        $replaced = preg::replace_callback($this->definition->pattern, $this->callback($callback, $flags), $this->subject->getSubject(), $this->limit, $count, $flags);
        $this->listener->replaced($count);
        return $replaced;
    }

    private function callback(AbstractionCalled $callback, int $flags): \Closure
    {
        $index = 0;
        return static function (array $match) use ($callback, &$index, $flags): string {
            if ($flags === 0) {
                return $callback->replaceLegacy(new LegacyModel($match), $index++);
            }
            return $callback->replaceStandard(new StandardModel($match), $index++);
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
