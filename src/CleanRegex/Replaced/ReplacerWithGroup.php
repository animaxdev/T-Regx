<?php
namespace TRegx\CleanRegex\Replaced;

use TRegx\CleanRegex\Exception\NonexistentGroupException;
use TRegx\CleanRegex\Internal\Definition;
use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\Model\GroupAware;
use TRegx\CleanRegex\Internal\Model\LightweightGroupAware;
use TRegx\CleanRegex\Internal\Subject;
use TRegx\CleanRegex\Replaced\ByMap\MissingGroupHandler;
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

    public function __construct(Definition $definition, Subject $subject, int $limit)
    {
        $this->definition = $definition;
        $this->subject = $subject;
        $this->limit = $limit;
        $this->groupAware = new LightweightGroupAware($definition);
    }

    public function replace($nameOrIndex, MissingGroupHandler $handler): string
    {
        return $this->replaceByGroup(GroupKey::of($nameOrIndex), $handler);
    }

    private function replaceByGroup(GroupKey $groupKey, MissingGroupHandler $handler): string
    {
        $nameOrIndex = $groupKey->nameOrIndex();
        $result = preg::replace_callback($this->definition->pattern, function (array $matches) use ($handler, $groupKey, $nameOrIndex) {
            if (\array_key_exists($nameOrIndex, $matches)) {
                return $matches[$nameOrIndex];
            }
            if (!$this->groupAware->hasGroup($nameOrIndex)) {
                throw new NonexistentGroupException($groupKey);
            }
            return $handler->handle($groupKey, $matches[0]);
        }, $this->subject->getSubject(), $this->limit, $count);
        if ($count === 0) {
            if (!$this->groupAware->hasGroup($nameOrIndex)) {
                throw new NonexistentGroupException($groupKey);
            }
        }
        return $result;
    }
}
