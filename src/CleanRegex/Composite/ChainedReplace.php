<?php
namespace TRegx\CleanRegex\Composite;

use TRegx\CleanRegex\Internal\Definition;
use TRegx\CleanRegex\Internal\Replace\By\NonReplaced\SubjectRs;
use TRegx\CleanRegex\Internal\Replace\Counting\IgnoreCounting;
use TRegx\CleanRegex\Internal\Replace\ReplaceReferences;
use TRegx\CleanRegex\Internal\StringSubject;
use TRegx\CleanRegex\Internal\Subject;
use TRegx\CleanRegex\Replace\Callback\MatchStrategy;
use TRegx\CleanRegex\Replace\Callback\ReplacePatternCallbackInvoker;
use TRegx\SafeRegex\preg;

class ChainedReplace
{
    /** @var Definition[] */
    private $definitions;
    /** @var Subject */
    private $subject;
    /** @var SubjectRs */
    private $substitute;

    public function __construct(array $definitions, Subject $subject, SubjectRs $substitute)
    {
        $this->definitions = $definitions;
        $this->subject = $subject;
        $this->substitute = $substitute;
    }

    public function with(string $replacement): string
    {
        return $this->withReferences(ReplaceReferences::quote($replacement));
    }

    public function withReferences(string $replacement): string
    {
        $subject = $this->subject->getSubject();
        foreach ($this->definitions as $definition) {
            $subject = preg::replace($definition->pattern, $replacement, $subject);
        }
        return $subject;
    }

    public function callback(callable $callback): string
    {
        $subject = $this->subject->getSubject();
        foreach ($this->definitions as $definition) {
            $subject = $this->replaceNext($definition, $subject, $callback);
        }
        return $subject;
    }

    private function replaceNext(Definition $definition, string $subject, callable $callback): string
    {
        $invoker = new ReplacePatternCallbackInvoker($definition, new StringSubject($subject), -1, $this->substitute, new IgnoreCounting());
        return $invoker->invoke($callback, new MatchStrategy());
    }
}
