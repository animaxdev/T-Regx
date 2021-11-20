<?php
namespace TRegx\CleanRegex\Replaced;

use TRegx\CleanRegex\Exception\GroupNotMatchedException;
use TRegx\CleanRegex\Exception\NonexistentGroupException;
use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\Message\Replace\Map\ForGroupMessage;
use TRegx\CleanRegex\Internal\Model\GroupAware;

class GroupMapReplacer implements MapReplacer
{
    /** @var GroupAware */
    private $groupAware;
    /** @var GroupKey */
    private $group;
    /** @var MissingGroupHandler */
    private $handler;

    public function __construct(GroupAware $groupAware, GroupKey $group, MissingGroupHandler $handler)
    {
        $this->groupAware = $groupAware;
        $this->group = $group;
        $this->handler = $handler;
    }

    public function replaceGroup(Replacements $replacements, array $matches): string
    {
        if (\array_key_exists($this->group->nameOrIndex(), $matches)) {
            if ($matches[$this->group->nameOrIndex()] === '') {
                throw GroupNotMatchedException::forReplacement($this->group);
            }
            return $replacements->replaced($matches[$this->group->nameOrIndex()], $this->message($matches, $this->group));
        }
        if ($this->groupAware->hasGroup($this->group->nameOrIndex())) {
            return $this->handler->handle($this->group, $matches[0]);
        }
        throw new NonexistentGroupException($this->group);
    }

    private function message(array $matches, GroupKey $group): ForGroupMessage
    {
        return new ForGroupMessage($matches[0], $group, $matches[$group->nameOrIndex()]);
    }
}
