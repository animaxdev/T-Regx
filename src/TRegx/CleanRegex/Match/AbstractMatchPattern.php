<?php
namespace TRegx\CleanRegex\Match;

use ArrayIterator;
use Countable;
use EmptyIterator;
use Iterator;
use TRegx\CleanRegex\Exception\CleanRegex\Messages\NoFirstElementFluentMessage;
use TRegx\CleanRegex\Exception\CleanRegex\Messages\Subject\FirstMatchMessage;
use TRegx\CleanRegex\Exception\CleanRegex\SubjectNotMatchedException;
use TRegx\CleanRegex\Internal\Factory\NotMatchedFluentOptionalWorker;
use TRegx\CleanRegex\Internal\Factory\NotMatchedOptionalWorker;
use TRegx\CleanRegex\Internal\GroupLimit\GroupLimitFactory;
use TRegx\CleanRegex\Internal\GroupNameValidator;
use TRegx\CleanRegex\Internal\Match\Base\Base;
use TRegx\CleanRegex\Internal\Match\Base\FilteredBaseDecorator;
use TRegx\CleanRegex\Internal\Match\FlatMapper;
use TRegx\CleanRegex\Internal\Match\MatchAll\LazyMatchAllFactory;
use TRegx\CleanRegex\Internal\Match\Predicate;
use TRegx\CleanRegex\Internal\Model\Factory\MatchObjectFactoryImpl;
use TRegx\CleanRegex\Internal\Model\GroupPolyfillDecorator;
use TRegx\CleanRegex\Internal\OffsetLimit\MatchOffsetLimitFactory;
use TRegx\CleanRegex\Internal\PatternLimit;
use TRegx\CleanRegex\Match\Details\Match;
use TRegx\CleanRegex\Match\Details\MatchImpl;
use TRegx\CleanRegex\Match\Details\NotMatched;
use TRegx\CleanRegex\Match\ForFirst\MatchedOptional;
use TRegx\CleanRegex\Match\ForFirst\NotMatchedOptional;
use TRegx\CleanRegex\Match\ForFirst\Optional;
use TRegx\CleanRegex\Match\Offset\MatchOffsetLimit;

abstract class AbstractMatchPattern implements MatchPatternInterface, PatternLimit, Countable
{
    /** @var Base */
    protected $base;

    public function __construct(Base $base)
    {
        $this->base = $base;
    }

    abstract public function test(): bool;

    public function fails(): bool
    {
        return !$this->test();
    }

    public function all(): array
    {
        return $this->base->matchAll()->getAll();
    }

    /**
     * @param callable|null $consumer
     * @return string|mixed
     * @throws SubjectNotMatchedException
     */
    public function first(callable $consumer = null)
    {
        $match = $this->base->matchOffset();
        if (!$match->matched()) {
            throw SubjectNotMatchedException::forFirst($this->base);
        }
        if ($consumer === null) {
            return $match->getText();
        }
        $factory = new LazyMatchAllFactory($this->base);
        $polyfill = new GroupPolyfillDecorator($match, $factory, 0);
        $matchObject = new MatchImpl($this->base, 0, 1, $polyfill, $factory, $this->base->getUserData());
        return $consumer($matchObject);
    }

    public function only(int $limit): array
    {
        return (new MatchOnly($this->base, $limit))->get();
    }

    public function forEach(callable $consumer): void
    {
        foreach ($this->getMatchObjects() as $object) {
            $consumer($object);
        }
    }

    public function iterate(callable $consumer): void
    {
        $this->forEach($consumer);
    }

    public function map(callable $mapper): array
    {
        return \array_map($mapper, $this->getMatchObjects());
    }

    public function flatMap(callable $mapper): array
    {
        return (new FlatMapper($this->getMatchObjects(), $mapper))->get();
    }

    public function unique(): array
    {
        return \array_values(\array_unique($this->all()));
    }

    /**
     * @param callable $consumer
     * @return Optional
     */
    public function forFirst(callable $consumer): Optional
    {
        $matches = $this->base->matchAllOffsets();
        if ($matches->matched()) {
            $result = $consumer($matches->getFirstMatchObject(new MatchObjectFactoryImpl($this->base, 1, $this->base->getUserData())));
            return new MatchedOptional($result);
        }
        return new NotMatchedOptional(
            new NotMatchedOptionalWorker(
                new FirstMatchMessage(),
                $this->base,
                new NotMatched($matches, $this->base))
        );
    }

    /**
     * @param string|int $nameOrIndex
     * @return GroupLimit
     */
    public function group($nameOrIndex): GroupLimit
    {
        (new GroupNameValidator($nameOrIndex))->validate();
        return (new GroupLimitFactory($this->base, $nameOrIndex))->create();
    }

    public function offsets(): MatchOffsetLimit
    {
        return (new MatchOffsetLimitFactory($this->base, 0))->create();
    }

    abstract public function count(): int;

    public function iterator(): Iterator
    {
        $objects = $this->getMatchObjects();
        if (empty($objects)) {
            return new EmptyIterator();
        }
        return new ArrayIterator($objects);
    }

    public function filter(callable $predicate): FilteredMatchPattern
    {
        return new FilteredMatchPattern(new FilteredBaseDecorator($this->base, new Predicate($predicate)));
    }

    public function fluent(): FluentMatchPattern
    {
        return new FluentMatchPattern(
            $this->getMatchObjects(),
            new NotMatchedFluentOptionalWorker(new NoFirstElementFluentMessage(), $this->base)
        );
    }

    /**
     * @return Match[]
     */
    protected function getMatchObjects(): array
    {
        $factory = new MatchObjectFactoryImpl($this->base, -1, $this->base->getUserData());
        return $this->base->matchAllOffsets()->getMatchObjects($factory);
    }
}
