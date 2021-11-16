<?php
namespace TRegx\CleanRegex\Replaced;

use TRegx\CleanRegex\Internal\Definition;
use TRegx\CleanRegex\Internal\Subject;

class ExactListenerFactory implements ListenerFactory
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

    public function create(int $limit): Listener
    {
        return new ExactListener(new OccurrencesAmount($this->definition, $this->subject, $limit), $limit);
    }
}
