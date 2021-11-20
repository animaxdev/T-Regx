<?php
namespace TRegx\CleanRegex\Replaced;

use TRegx\CleanRegex\Exception\GroupNotMatchedException;
use TRegx\CleanRegex\Exception\NonexistentGroupException;
use TRegx\CleanRegex\Internal\Definition;
use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\Model\GroupAware;
use TRegx\CleanRegex\Internal\Model\LightweightGroupAware;
use TRegx\CleanRegex\Internal\Subject;
use TRegx\SafeRegex\preg;

class ReplacerByMap
{
    /** @var Definition */
    private $definition;
    /** @var Subject */
    private $subject;
    /** @var int */
    private $limit;
    /** @var GroupAware */
    private $groupAware;

    public function __construct(Definition $definition, Subject $subject, int $limit)
    {
        $this->definition = $definition;
        $this->subject = $subject;
        $this->limit = $limit;
        $this->groupAware = new LightweightGroupAware($this->definition);
    }

    public function byMap(GroupKey $group, array $replacements, MissingGroupHandler $handler): string
    {
        return $this->replaceByMap($handler, $group, new Replacements($replacements));
    }

    private function replaceByMap(MissingGroupHandler $handler, GroupKey $group, Replacements $replacements)
    {
        $result = preg::replace_callback($this->definition->pattern,
            function (array $matches) use ($handler, $group, $replacements): string {
                return $this->replaceGroup($group, $matches, $replacements, $handler);
            },
            $this->subject->getSubject(),
            $this->limit,
            $count);
        if ($count === 0) {
            if (!$this->groupAware->hasGroup($group->nameOrIndex())) {
                throw new NonexistentGroupException($group);
            }
        }
        return $result;
    }

    private function replaceGroup(GroupKey $group, array $matches, Replacements $replacements, MissingGroupHandler $handler): string
    {
        if (!\array_key_exists($group->nameOrIndex(), $matches)) {
            if ($this->groupAware->hasGroup($group->nameOrIndex())) {
                return $handler->handle($group, $matches[0]);
            }
            throw new NonexistentGroupException($group);
        }
        if ($matches[$group->nameOrIndex()] === '') {
            throw GroupNotMatchedException::forReplacement($group);
        }
        return $replacements->replaced($group, $matches[$group->nameOrIndex()], $matches[0]);
    }
}
