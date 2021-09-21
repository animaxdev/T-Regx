<?php
namespace TRegx\CleanRegex\Internal\Prepared;

use Generator;
use TRegx\CleanRegex\Internal\Flags;
use TRegx\CleanRegex\Internal\Prepared\Parser\Consumer\CommentConsumer;
use TRegx\CleanRegex\Internal\Prepared\Parser\Consumer\ControlConsumer;
use TRegx\CleanRegex\Internal\Prepared\Parser\Consumer\EscapeConsumer;
use TRegx\CleanRegex\Internal\Prepared\Parser\Consumer\GroupCloseConsumer;
use TRegx\CleanRegex\Internal\Prepared\Parser\Consumer\GroupConsumer;
use TRegx\CleanRegex\Internal\Prepared\Parser\Consumer\LiteralConsumer;
use TRegx\CleanRegex\Internal\Prepared\Parser\Consumer\PlaceholderConsumer;
use TRegx\CleanRegex\Internal\Prepared\Parser\Consumer\PosixConsumer;
use TRegx\CleanRegex\Internal\Prepared\Parser\Consumer\QuoteConsumer;
use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\Entity;
use TRegx\CleanRegex\Internal\Prepared\Parser\Feed\Feed;
use TRegx\CleanRegex\Internal\Prepared\Parser\PcreParser;
use TRegx\CleanRegex\Internal\Prepared\Word\CompositeWord;
use TRegx\CleanRegex\Internal\Prepared\Word\Word;

class PatternAsEntities
{
    /** @var PcreParser */
    private $pcreParser;

    public function __construct(string $pattern, Flags $flags, PlaceholderConsumer $placeholderConsumer)
    {
        $this->pcreParser = new PcreParser(new Feed($pattern), $flags, [
            new ControlConsumer(),
            new QuoteConsumer(),
            new EscapeConsumer(),
            new GroupConsumer(),
            new GroupCloseConsumer(),
            $placeholderConsumer,
            new PosixConsumer(),
            new CommentConsumer(),
            new LiteralConsumer(),
        ]);
    }

    /**
     * @return Entity[]
     */
    public function entities(): array
    {
        return $this->pcreParser->entities();
    }

    public function word(): Word
    {
        return new CompositeWord(\iterator_to_array($this->words()));
    }

    private function words(): Generator
    {
        foreach ($this->pcreParser->entities() as $entity) {
            yield $entity->word();
        }
    }
}
