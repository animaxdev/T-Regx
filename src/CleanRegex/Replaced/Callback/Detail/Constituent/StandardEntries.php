<?php
namespace TRegx\CleanRegex\Replaced\Callback\Detail\Constituent;

use TRegx\CleanRegex\Internal\Model\Match\GroupEntries;

class StandardEntries implements GroupEntries
{
    /** @var array */
    private $matchOffset;

    public function __construct(array $matchOffset)
    {
        $this->matchOffset = $matchOffset;
    }

    public function groupTexts(): array
    {
        $texts = [];
        foreach ($this->matchOffset as $group => [$text, $offset]) {
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
        foreach ($this->matchOffset as $group => [$text, $offset]) {
            if ($offset === -1) {
                $offsets[$group] = null;
            } else {
                $offsets[$group] = $offset;
            }
        }
        return $offsets;
    }
}
