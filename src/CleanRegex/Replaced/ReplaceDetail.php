<?php
namespace TRegx\CleanRegex\Replaced;

use TRegx\CleanRegex\Exception\GroupNotMatchedException;
use TRegx\CleanRegex\Exception\IntegerFormatException;
use TRegx\CleanRegex\Exception\IntegerOverflowException;
use TRegx\CleanRegex\Exception\NonexistentGroupException;
use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\GroupNames;
use TRegx\CleanRegex\Internal\Match\Details\GroupsCount;
use TRegx\CleanRegex\Internal\Match\Details\MatchedGroup;
use TRegx\CleanRegex\Internal\Model\GroupAware;
use TRegx\CleanRegex\Internal\Number\Base;
use TRegx\CleanRegex\Internal\Number\NumberFormatException;
use TRegx\CleanRegex\Internal\Number\NumberOverflowException;
use TRegx\CleanRegex\Internal\Number\StringNumber;
use TRegx\CleanRegex\Internal\Offset\SubjectCoordinates;
use TRegx\CleanRegex\Internal\Subject;
use TRegx\CleanRegex\Match\Details\Detail;
use TRegx\CleanRegex\Match\Details\DuplicateName;
use TRegx\CleanRegex\Match\Details\Groups\IndexedGroups;
use TRegx\CleanRegex\Match\Details\Groups\NamedGroups;

class ReplaceDetail implements Detail
{
    /** @var Subject */
    private $subject;
    /** @var GroupAware */
    private $groupAware;
    /** @var array */
    private $match;
    /** @var int */
    private $index;
    /** @var int */
    private $limit;
    /** @var GroupNames */
    private $groupNames;
    /** @var GroupsCount */
    private $groupsCount;
    /** @var MatchedGroup */
    private $matchedGroup;
    /** @var SubjectCoordinates */
    private $coords;

    public function __construct(Subject    $subject,
                                GroupAware $groupAware,
                                array      $match,
                                int        $index,
                                int        $limit,
                                int        $offset,
                                array      $matches)
    {
        $this->subject = $subject;
        $this->groupAware = $groupAware;
        $this->match = $match;
        $this->index = $index;
        $this->limit = $limit;
        $this->coords = new SubjectCoordinates(new ReplaceEntry($match[0], $offset), $subject);
        $this->groupNames = new GroupNames($groupAware);
        $this->groupsCount = new GroupsCount($groupAware);
        $this->matchedGroup = new MatchedGroup($match, $matches);
    }

    public function subject(): string
    {
        return $this->subject->getSubject();
    }

    public function groupNames(): array
    {
        return $this->groupNames->groupNames();
    }

    public function groupsCount(): int
    {
        return $this->groupsCount->groupsCount();
    }

    public function hasGroup($nameOrIndex): bool
    {
        GroupKey::of($nameOrIndex);
        return $this->groupAware->hasGroup($nameOrIndex);
    }

    public function text(): string
    {
        return $this->match[0];
    }

    public function textLength(): int
    {
        return $this->coords->characterLength();
    }

    public function textByteLength(): int
    {
        return $this->coords->byteLength();
    }

    public function toInt(int $base = null): int
    {
        $number = new StringNumber($this->text());
        $theBase = new Base($base);
        try {
            return $number->asInt($theBase);
        } catch (NumberFormatException $exception) {
            throw IntegerFormatException::forMatch($this->text(), $theBase);
        } catch (NumberOverflowException $exception) {
            throw IntegerOverflowException::forMatch($this->text(), $theBase);
        }
    }

    public function isInt(int $base = null): bool
    {
        $number = new StringNumber($this->text());
        $theBase = new Base($base);
        try {
            $number->asInt($theBase);
        } catch (NumberFormatException|NumberOverflowException $exception) {
            return false;
        }
        return true;
    }

    public function index(): int
    {
        return $this->index;
    }

    public function limit(): int
    {
        return $this->limit;
    }

    public function get($nameOrIndex): string
    {
        if (\array_key_exists($nameOrIndex, $this->match)) {
            return $this->match[$nameOrIndex];
        }
        if ($this->groupAware->hasGroup($nameOrIndex)) {
            throw GroupNotMatchedException::forGet(GroupKey::of($nameOrIndex));
        }
        throw new NonexistentGroupException(GroupKey::of($nameOrIndex));
    }

    public function group($nameOrIndex)
    {
        // TODO: Implement group() method.
    }

    public function usingDuplicateName(): DuplicateName
    {
        // TODO: Implement usingDuplicateName() method.
    }

    public function groups(): IndexedGroups
    {
        // TODO: Implement groups() method.
    }

    public function namedGroups(): NamedGroups
    {
        // TODO: Implement namedGroups() method.
    }

    public function matched($nameOrIndex): bool
    {
        return $this->matchedGroup->matched(GroupKey::of($nameOrIndex));
    }

    public function all(): array
    {
        // TODO: Implement all() method.
    }

    public function offset(): int
    {
        return $this->coords->characterOffset();
    }

    public function tail(): int
    {
        return $this->coords->characterTail();
    }

    public function byteOffset(): int
    {
        return $this->coords->byteOffset();
    }

    public function byteTail(): int
    {
        return $this->coords->byteTail();
    }

    public function setUserData($userData): void
    {
        // TODO: Implement setUserData() method.
    }

    public function getUserData()
    {
        // TODO: Implement getUserData() method.
    }

    public function __toString(): string
    {
        return $this->text();
    }
}
