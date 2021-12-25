<?php
namespace TRegx\CleanRegex\Replaced\Callback;

use TRegx\CleanRegex\Internal\Model\GroupAware;
use TRegx\CleanRegex\Internal\Subject;
use TRegx\CleanRegex\Replaced\Callback\Detail\DetailCallback;
use TRegx\CleanRegex\Replaced\Preg\Occurrences;

class ReplacerCallback
{
    /** @var GroupAware */
    private $aware;
    /** @var Subject */
    private $subject;
    /** @var int */
    private $limit;
    /** @var Occurrences */
    private $occurrences;
    /** @var ReplacePlan */
    private $replacePlan;

    public function __construct(GroupAware $aware, Subject $subject, int $limit, Occurrences $occurrences, ReplacePlan $replacePlan)
    {
        $this->aware = $aware;
        $this->subject = $subject;
        $this->limit = $limit;
        $this->occurrences = $occurrences;
        $this->replacePlan = $replacePlan;
    }

    public function replaced(ReplaceFunction $function): string
    {
        return $this->replacePlan->replaced(new DetailCallback($this->aware, $this->subject, $this->limit, $this->occurrences, $function));
    }
}
