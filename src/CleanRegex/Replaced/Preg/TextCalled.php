<?php
namespace TRegx\CleanRegex\Replaced\Preg;

use TRegx\CleanRegex\Internal\Definition;
use TRegx\CleanRegex\Internal\Subject;
use TRegx\CleanRegex\Replaced\Callback\TextCallback;
use TRegx\SafeRegex\preg;

class TextCalled
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

    public function replaced(TextCallback $callback): string
    {
        return preg::replace_callback($this->definition->pattern, static function (array $match) use ($callback): string {
            return $callback->replace($match[0]);
        }, $this->subject->getSubject(), $this->limit);
    }
}
