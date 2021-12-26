<?php
namespace TRegx\CleanRegex\Replaced\Group;

use TRegx\CleanRegex\Exception\NonexistentGroupException;
use TRegx\CleanRegex\Internal\Definition;
use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\Model\GroupAware;
use TRegx\CleanRegex\Internal\Subject;
use TRegx\CleanRegex\Replaced\Expectation\Listener;
use TRegx\SafeRegex\preg;

class ReplacerWithGroup
{
    /** @var Definition */
    private $definition;
    /** @var Subject */
    private $subject;
    /** @var int */
    private $limit;
    /** @var GroupAware */
    private $groupAware;
    /** @var SequenceMatchAware */
    private $matchAware;
    /** @var Listener */
    private $listener;

    public function __construct(Definition         $definition,
                                Subject            $subject,
                                int                $limit,
                                GroupAware         $groupAware,
                                SequenceMatchAware $matchAware,
                                Listener           $listener)
    {
        $this->definition = $definition;
        $this->subject = $subject;
        $this->limit = $limit;
        $this->groupAware = $groupAware;
        $this->matchAware = $matchAware;
        $this->listener = $listener;
    }

    public function replaced(GroupKey $group, MissingGroupHandler $handler, GroupOperation $operation): string
    {
        [$replaced, $count] = $this->pregReplaceCallback($this->callback($group, $operation, $handler));
        if ($count === 0 && !$group->exists($this->groupAware)) {
            throw new NonexistentGroupException($group);
        }
        $this->listener->replaced($count);
        return $replaced;
    }

    private function pregReplaceCallback(callable $callback): array
    {
        $replaced = preg::replace_callback($this->definition->pattern, $callback, $this->subject->getSubject(), $this->limit, $count);
        return [$replaced, $count];
    }

    private function callback(GroupKey $group, GroupOperation $operation, MissingGroupHandler $handler): callable
    {
        return function (array $matches) use ($operation, $group, $handler): string {
            $result = $this->calledBack($group, $operation, $handler, $matches);
            $this->matchAware->next();
            return $result;
        };
    }

    private function calledBack(GroupKey $group, GroupOperation $operation, MissingGroupHandler $handler, array $matches): string
    {
        if ($this->groupMatched($group, $matches)) {
            return $operation->make($matches[$group->nameOrIndex()], $matches[0]);
        }
        return $handler->handle($group, $matches[0]);
    }

    private function groupMatched(GroupKey $group, array $matches): bool
    {
        $nameOrIndex = $group->nameOrIndex();
        if (\array_key_exists($nameOrIndex, $matches)) {
            return $matches[$nameOrIndex] !== '' || $this->matchAware->matched($group);
        }
        if ($group->exists($this->groupAware)) {
            return false;
        }
        throw new NonexistentGroupException($group);
    }
}
