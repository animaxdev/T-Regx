<?php
namespace TRegx\CleanRegex\Internal\Delimiter;

use TRegx\CleanRegex\Internal\Flags;
use TRegx\CleanRegex\Internal\Prepared\Quotable\Quotable;

class Delimiter
{
    /** @var string */
    private $delimiter;

    public function __construct(string $delimiter)
    {
        $this->delimiter = $delimiter;
    }

    public function delimited(Quotable $quotable, Flags $flags): string
    {
        return $this->delimiter . $quotable->quote($this->delimiter) . $this->delimiter . $flags;
    }

    public static function suitable(string $delimiterable): Delimiter
    {
        $delimiters = ['/', '#', '%', '~', '+', '!', '@', '_', ';', '`', '-', '=', ',', "\1"];
        foreach ($delimiters as $delimiter) {
            if (\strpos($delimiterable, $delimiter) === false) {
                return new Delimiter($delimiter);
            }
        }
        throw new UndelimiterablePatternException();
    }
}
