<?php
namespace Test\Fakes\CleanRegex\Internal\Match\Stream;

use TRegx\CleanRegex\Internal\Match\Stream\Upstream;

class FirstKeyStream implements Upstream
{
    /** @var string|int */
    private $firstKey;

    public function __construct($firstKey)
    {
        $this->firstKey = $firstKey;
    }

    public function firstKey()
    {
        return $this->firstKey;
    }

    public function first()
    {
        throw new \AssertionError("Failed to assert that stream first value wasn't used");
    }

    public function all(): array
    {
        throw new \AssertionError("Failed to assert that stream feed wasn't used");
    }
}
