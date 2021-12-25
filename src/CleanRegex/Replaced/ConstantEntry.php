<?php
namespace TRegx\CleanRegex\Replaced;

use TRegx\CleanRegex\Internal\Model\Match\Entry;

class ConstantEntry implements Entry
{
    /** @var string */
    private $text;
    /** @var int */
    private $byteOffset;

    public function __construct(string $text, int $byteOffset)
    {
        $this->text = $text;
        $this->byteOffset = $byteOffset;
    }

    public function text(): string
    {
        return $this->text;
    }

    public function byteOffset(): int
    {
        return $this->byteOffset;
    }
}
