<?php
namespace TRegx\CleanRegex\Replaced\Preg;

class ByteOffset
{
    /** @var Analyzed */
    private $analyzed;
    /** @var int */
    private $index;

    public function __construct(Analyzed $analyzed, int $index)
    {
        $this->analyzed = $analyzed;
        $this->index = $index;
    }

    public function byteOffset(): int
    {
        // TODO perhaps you could do preg_match first, for the offset,
        // and only call preg_match_all() if it's uncertain, and only
        // for index 1
        return $this->analyzed->analyzedSubject()[0][$this->index][1];
    }
}
