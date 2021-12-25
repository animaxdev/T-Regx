<?php
namespace TRegx\CleanRegex\Replaced\Preg;

use TRegx\CleanRegex\Internal\Model\Match\GroupEntries;

class MatchAllEntries implements GroupEntries
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

    public function groupTexts(): array
    {
        $texts = [];
        foreach ($this->allMatchOffset as $group => $match) {
            [$text, $offset] = $match[$this->index];
            if ($offset === -1) {
                $texts[$group] = null;
            } else {
                $texts[$group] = $text;
            }
        }
        return $texts;
    }

    public function groupOffsets(): array
    {
        $offsets = [];
        foreach ($this->allMatchOffset as $group => $match) {
            [$text, $offset] = $match[$this->index];
            if ($offset === -1) {
                $offsets[$group] = null;
            } else {
                $offsets[$group] = $offset;
            }
        }
        return $offsets;
    }
}
