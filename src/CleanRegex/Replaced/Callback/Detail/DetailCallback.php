<?php
namespace TRegx\CleanRegex\Replaced\Callback\Detail;

use TRegx\CleanRegex\Internal\Model\GroupAware;
use TRegx\CleanRegex\Internal\Subject;
use TRegx\CleanRegex\Replaced\Callback\Detail\Constituent\Constituent;
use TRegx\CleanRegex\Replaced\Callback\ReplaceFunction;
use TRegx\CleanRegex\Replaced\Preg\AllOccurrences;

class DetailCallback
{
    /** @var GroupAware */
    private $groupAware;
    /** @var Subject */
    private $subject;
    /** @var int */
    private $limit;
    /** @var AllOccurrences */
    private $occurrences;
    /** @var ReplaceFunction */
    private $function;

    public function __construct(GroupAware $groupAware, Subject $subject, int $limit, AllOccurrences $occurrences, ReplaceFunction $function)
    {
        $this->groupAware = $groupAware;
        $this->subject = $subject;
        $this->limit = $limit;
        $this->occurrences = $occurrences;
        $this->function = $function;
    }

    public function replace(Constituent $constituent, int $index): string
    {
        return $this->function->apply(new ReplaceDetail($this->groupAware, $this->subject, $index, $this->limit, $constituent, $this->occurrences));
    }
}
