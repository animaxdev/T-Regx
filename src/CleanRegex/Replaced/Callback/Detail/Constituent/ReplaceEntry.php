<?php
namespace TRegx\CleanRegex\Replaced\Callback\Detail\Constituent;

use TRegx\CleanRegex\Internal\Model\Match\Entry;
use TRegx\CleanRegex\Replaced\Preg\ByteOffset;

class ReplaceEntry implements Entry
{
    /** @var string */
    private $text;
    /** @var ByteOffset */
    private $byteOffset;

    public function __construct(string $text, ByteOffset $byteOffset)
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
        return $this->byteOffset->byteOffset();
    }
}
