<?php
namespace TRegx\CleanRegex\Replaced;

use TRegx\CleanRegex\Exception\GroupNotMatchedException;
use TRegx\CleanRegex\Exception\IntegerFormatException;
use TRegx\CleanRegex\Exception\IntegerOverflowException;
use TRegx\CleanRegex\Exception\NonexistentGroupException;
use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\GroupNames;
use TRegx\CleanRegex\Internal\Match\Details\GroupsCount;
use TRegx\CleanRegex\Internal\Model\GroupAware;
use TRegx\CleanRegex\Internal\Number\Base;
use TRegx\CleanRegex\Internal\Number\NumberFormatException;
use TRegx\CleanRegex\Internal\Number\NumberOverflowException;
use TRegx\CleanRegex\Internal\Number\StringNumber;
use TRegx\CleanRegex\Internal\Offset\ByteOffset;
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
    /** @var ByteOffset */
    private $byteOffset;
    /** @var GroupNames */
    private $groupNames;
    /** @var GroupsCount */
    private $groupsCount;

    public function __construct(Subject    $subject,
                                GroupAware $groupAware,
                                array      $match,
                                int        $index,
                                int        $limit,
                                int        $offset)
    {
        $this->subject = $subject;
        $this->groupAware = $groupAware;
        $this->match = $match;
        $this->index = $index;
        $this->limit = $limit;
        $this->byteOffset = new ByteOffset($offset);
        $this->groupNames = new GroupNames($groupAware);
        $this->groupsCount = new GroupsCount($groupAware);
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
        // TODO: Implement textLength() method.
    }

    public function textByteLength(): int
    {
        // TODO: Implement textByteLength() method.
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
        } catch (NumberFormatException | NumberOverflowException $exception) {
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
        // TODO: Implement matched() method.
    }

    public function all(): array
    {
        // TODO: Implement all() method.
    }

    public function offset(): int
    {
        return $this->byteOffset->characters($this->subject->getSubject());
    }

    public function tail(): int
    {
        // TODO: Implement tail() method.
    }

    public function byteOffset(): int
    {
        return $this->byteOffset->bytes();
    }

    public function byteTail(): int
    {
        // TODO: Implement byteTail() method.
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
