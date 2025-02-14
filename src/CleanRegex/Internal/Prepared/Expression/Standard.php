<?php
namespace TRegx\CleanRegex\Internal\Prepared\Expression;

use TRegx\CleanRegex\Exception\ExplicitDelimiterRequiredException;
use TRegx\CleanRegex\Internal\Delimiter\Delimiter;
use TRegx\CleanRegex\Internal\Delimiter\UndelimiterablePatternException;
use TRegx\CleanRegex\Internal\Expression\Expression;
use TRegx\CleanRegex\Internal\Flags;
use TRegx\CleanRegex\Internal\Prepared\Orthography\Spelling;
use TRegx\CleanRegex\Internal\Prepared\Parser\Consumer\LiteralPlaceholderConsumer;
use TRegx\CleanRegex\Internal\Prepared\PatternAsEntities;
use TRegx\CleanRegex\Internal\Prepared\Phrase\Phrase;

class Standard implements Expression
{
    use PredefinedExpression;

    /** @var Spelling */
    private $spelling;
    /** @var PatternAsEntities */
    private $patternAsEntities;

    public function __construct(Spelling $spelling)
    {
        $this->spelling = $spelling;
        $this->patternAsEntities = new PatternAsEntities($spelling->pattern(), $spelling->flags(), new LiteralPlaceholderConsumer());
    }

    protected function phrase(): Phrase
    {
        return $this->patternAsEntities->phrase();
    }

    protected function delimiter(): Delimiter
    {
        try {
            return $this->spelling->delimiter();
        } catch (UndelimiterablePatternException $exception) {
            throw ExplicitDelimiterRequiredException::forStandard($this->spelling->pattern());
        }
    }

    protected function flags(): Flags
    {
        return $this->spelling->flags();
    }

    protected function undevelopedInput(): string
    {
        return $this->spelling->undevelopedInput();
    }
}
