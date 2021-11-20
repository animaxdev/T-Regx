<?php
namespace TRegx\CleanRegex\Replaced;

use TRegx\CleanRegex\Exception\GroupNotMatchedException;
use TRegx\CleanRegex\Exception\MissingReplacementKeyException;
use TRegx\CleanRegex\Exception\NonexistentGroupException;
use TRegx\CleanRegex\Internal\Definition;
use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\InvalidArgument;
use TRegx\CleanRegex\Internal\Message\Replace\Map\ForGroupMessage;
use TRegx\CleanRegex\Internal\Message\Replace\Map\ForMatchMessage;
use TRegx\CleanRegex\Internal\Model\GroupAware;
use TRegx\CleanRegex\Internal\Model\LightweightGroupAware;
use TRegx\CleanRegex\Internal\Subject;
use TRegx\CleanRegex\Internal\Type\ValueType;
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

    public function byMap(GroupKey $group, array $replacements): string
    {
        $this->validate($replacements);
        $result = preg::replace_callback($this->definition->pattern, function (array $matches) use ($group, $replacements): string {
            return $this->replaceGroup($group, $matches, $replacements);
        }, $this->subject->getSubject(), $this->limit, $count);
        if ($count === 0) {
            if (!$this->groupAware->hasGroup($group->nameOrIndex())) {
                throw new NonexistentGroupException($group);
            }
        }
        return $result;
    }

    private function replaceGroup(GroupKey $group, array $matches, array $replacements): string
    {
        if (!\array_key_exists($group->nameOrIndex(), $matches)) {
            if ($this->groupAware->hasGroup($group->nameOrIndex())) {
                throw GroupNotMatchedException::forReplacement($group);
            }
            throw new NonexistentGroupException($group);
        }
        if ($matches[$group->nameOrIndex()] === '') {
            throw GroupNotMatchedException::forReplacement($group);
        }
        return $this->replacement($matches, $group, $replacements);
    }

    private function replacement(array $matches, GroupKey $group, array $replacements): string
    {
        $occurrence = $matches[$group->nameOrIndex()];
        if (\array_key_exists($occurrence, $replacements)) {
            return $replacements[$occurrence];
        }
        throw new MissingReplacementKeyException($this->message($group, $matches[0], $occurrence)->getMessage());
    }

    private function message(GroupKey $group, string $match, string $occurrence)
    {
        if ($group->full()) {
            return new ForMatchMessage($match);
        }
        return new ForGroupMessage($match, $group, $occurrence);
    }

    private function validate(array $occurrencesAndReplacements): void
    {
        foreach ($occurrencesAndReplacements as $replacement) {
            if (!\is_string($replacement)) {
                throw InvalidArgument::typeGiven('Invalid replacement map value. Expected string', new ValueType($replacement));
            }
        }
    }
}
