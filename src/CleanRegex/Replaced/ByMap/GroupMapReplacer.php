<?php
namespace TRegx\CleanRegex\Replaced\ByMap;

use TRegx\CleanRegex\Exception\GroupNotMatchedException;
use TRegx\CleanRegex\Exception\NonexistentGroupException;
use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\Message\Replace\Map\ForGroupMessage;
use TRegx\CleanRegex\Internal\Model\GroupAware;
use TRegx\CleanRegex\Replaced\CalledBack;

class GroupMapReplacer
{
    /** @var CalledBack */
    private $calledBack;
    /** @var GroupAware */
    private $groupAware;
    /** @var MissingGroupHandler */
    private $handler;
    /** @var GroupKey */
    private $group;

    public function __construct(CalledBack $calledBack, GroupAware $groupAware, MissingGroupHandler $handler, GroupKey $group)
    {
        $this->calledBack = $calledBack;
        $this->groupAware = $groupAware;
        $this->handler = $handler;
        $this->group = $group;
    }

    public function replaced(Replacements $replacements): string
    {
        [$replaced, $counted] = $this->replacedAndCounted($replacements);
        if ($counted === 0 && !$this->groupExists()) {
            throw new NonexistentGroupException($this->group);
        }
        return $replaced;
    }

    private function replacedAndCounted(Replacements $replacements): array
    {
        return $this->calledBack->replacedAndCounted(function (array $matches) use ($replacements): string {
            return $this->replacedGroup($replacements, $matches);
        });
    }

    private function replacedGroup(Replacements $replacements, array $matches): string
    {
        if (\array_key_exists($this->group->nameOrIndex(), $matches)) {
            return $this->replacedMatchedGroup($matches, $replacements);
        }
        return $this->handledUnmatchedGroup($matches[0]);
    }

    private function replacedMatchedGroup(array $matches, Replacements $replacements): string
    {
        $occurrence = $matches[$this->group->nameOrIndex()];
        if ($occurrence === '') {
            throw GroupNotMatchedException::forReplacement($this->group);
        }
        return $replacements->replaced($occurrence, $this->message($matches));
    }

    private function handledUnmatchedGroup(string $original): string
    {
        if ($this->groupExists()) {
            return $this->handler->handle($this->group, $original);
        }
        throw new NonexistentGroupException($this->group);
    }

    private function message(array $matches): ForGroupMessage
    {
        return new ForGroupMessage($matches[0], $this->group, $matches[$this->group->nameOrIndex()]);
    }

    private function groupExists(): bool
    {
        return $this->groupAware->hasGroup($this->group->nameOrIndex());
    }
}
