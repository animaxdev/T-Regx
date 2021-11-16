<?php
namespace TRegx\CleanRegex\Replaced;

use TRegx\CleanRegex\Internal\Definition;
use TRegx\CleanRegex\Internal\Subject;

class ReplaceExpectationImpl implements ReplaceExpectation
{
    /** @var Definition */
    private $definition;
    /** @var Subject */
    private $subject;
    /** @var ReplacerWith */
    private $replacerWith;
    /** @var ReplacerCallback */
    private $replacerCallback;

    public function __construct(Definition $definition, Subject $subject)
    {
        $this->definition = $definition;
        $this->subject = $subject;
    }

    public function first(): ReplaceOperation
    {
        return new ReplaceOperationImpl($this->definition, $this->subject, 1, new AtLeastListener(1));
    }

    public function only(int $amount): ReplaceOperation
    {
        return new ReplaceOperationImpl($this->definition, $this->subject, $amount, new AtLeastListener($amount));
    }
}
