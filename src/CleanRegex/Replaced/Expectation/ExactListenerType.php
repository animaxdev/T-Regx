<?php
namespace TRegx\CleanRegex\Replaced\Expectation;

use TRegx\CleanRegex\Internal\Definition;
use TRegx\CleanRegex\Internal\Subject;

class ExactListenerType implements ListenerType
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

    public function listener(int $limit): Listener
    {
        return new ExactListener(new OccurrencesAmount($this->definition, $this->subject, $limit), $limit);
    }
}
