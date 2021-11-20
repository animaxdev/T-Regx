<?php
namespace TRegx\CleanRegex\Replaced;

use TRegx\CleanRegex\Exception\GroupNotMatchedException;
use TRegx\CleanRegex\Exception\IntegerFormatException;
use TRegx\CleanRegex\Exception\IntegerOverflowException;
use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
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
    }

    public function subject(): string
    {
        return $this->subject->getSubject();
    }

    public function groupNames(): array
    {
        // TODO: Implement groupNames() method.
    }

    public function groupsCount(): int
    {
        return count(\array_filter($this->groupAware->getGroupKeys(), 'is_int')) - 1;
    }

    public function hasGroup($nameOrIndex): bool
    {
        // TODO: Implement hasGroup() method.
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
        // TODO: Implement isInt() method.
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
        throw GroupNotMatchedException::forGet(GroupKey::of($nameOrIndex));
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
