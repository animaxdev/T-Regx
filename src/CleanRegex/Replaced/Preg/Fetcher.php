<?php
namespace TRegx\CleanRegex\Replaced\Preg;

use TRegx\CleanRegex\Internal\Model\Match\GroupEntries;

class Fetcher
{
    /** @var Analyzed */
    private $analyzed;

    public function __construct(Analyzed $analyzed)
    {
        $this->analyzed = $analyzed;
    }

    public function groupEntries(int $index): GroupEntries
    {
        return new MatchAllEntries($this->analyzed->analyzedSubject(), $index);
    }

    public function byteOffset(int $index): ByteOffset
    {
        return new ByteOffset($this->analyzed->analyzedSubject(), $index);
    }
}
