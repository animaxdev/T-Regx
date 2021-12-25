<?php
namespace TRegx\CleanRegex\Replaced\Callback\Detail;

use TRegx\CleanRegex\Exception\IntegerFormatException;
use TRegx\CleanRegex\Exception\IntegerOverflowException;
use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\GroupNames;
use TRegx\CleanRegex\Internal\Match\Details\GroupsCount;
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
use TRegx\CleanRegex\Replaced\Callback\Detail\Constituent\Constituent;
use TRegx\CleanRegex\Replaced\Callback\Detail\Group\Group;
use TRegx\CleanRegex\Replaced\Preg\Occurrences;
use TRegx\CleanRegex\Replaced\UserData;

class ReplaceDetail implements Detail
{
    /** @var Subject */
    private $subject;
    /** @var GroupAware */
    private $groupAware;
    /** @var string */
    private $text;
    /** @var int */
    private $index;
    /** @var int */
    private $limit;
    /** @var GroupNames */
    private $groupNames;
    /** @var GroupsCount */
    private $groupsCount;
    /** @var Group */
    private $matchedGroup;
    /** @var SubjectCoordinates */
    private $coords;
    /** @var UserData */
    private $userData;
    /** @var Occurrences */
    private $occurrences;
    /** @var IndexedGroups */
    private $indexedGroups;
    /** @var NamedGroups */
    private $namedGroups;

    public function __construct(GroupAware  $groupAware,
                                Subject     $subject,
                                int         $index,
                                int         $limit,
                                Constituent $constituent,
                                Occurrences $occurrences)
    {
        $this->subject = $subject;
        $this->groupAware = $groupAware;
        $this->text = $constituent->text();
        $this->index = $index;
        $this->limit = $limit;
        $this->coords = new SubjectCoordinates($constituent->entry(), $subject);
        $this->groupNames = new GroupNames($groupAware);
        $this->groupsCount = new GroupsCount($groupAware);
        $this->matchedGroup = $constituent->group();
        $this->userData = new UserData();
        $this->occurrences = $occurrences;
        $this->indexedGroups = new IndexedGroups($groupAware, $constituent->groupEntries(), $subject);
        $this->namedGroups = new NamedGroups($groupAware, $constituent->groupEntries(), $subject);
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
        return $this->text;
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
        return $this->matchedGroup->get(GroupKey::of($nameOrIndex));
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
        return $this->indexedGroups;
    }

    public function namedGroups(): NamedGroups
    {
        return $this->namedGroups;
    }

    public function matched($nameOrIndex): bool
    {
        return $this->matchedGroup->matched(GroupKey::of($nameOrIndex));
    }

    public function all(): array
    {
        return $this->occurrences->all();
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
        $this->userData->set($userData);
    }

    public function getUserData()
    {
        return $this->userData->get();
    }

    public function __toString(): string
    {
        return $this->text();
    }
}
