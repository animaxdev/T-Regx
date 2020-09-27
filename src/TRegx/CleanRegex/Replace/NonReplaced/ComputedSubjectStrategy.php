<?php
namespace TRegx\CleanRegex\Replace\NonReplaced;

class ComputedSubjectStrategy implements SubjectRs
{
    /** @var callable */
    private $mapper;

    public function __construct(callable $mapper)
    {
        $this->mapper = $mapper;
    }

    public function substitute(string $subject): ?string
    {
        return \call_user_func($this->mapper, $subject);
    }
}
