<?php
namespace TRegx\CleanRegex\Replaced\Preg;

class ByteOffset
{
    /** @var array[] */
    private $allMatchOffset;
    /** @var int */
    private $index;

    public function __construct(array $allMatchOffset, int $index)
    {
        $this->allMatchOffset = $allMatchOffset;
        $this->index = $index;
    }

    public function byteOffset(): int
    {
        return $this->allMatchOffset[0][$this->index][1];
    }
}
