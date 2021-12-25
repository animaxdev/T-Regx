<?php
namespace TRegx\CleanRegex\Replaced\Expectation;

use TRegx\CleanRegex\Exception\ReplacementExpectationFailedException;

class AtLeastListener implements Listener
{
    /** @var int */
    private $minimalReplacements;

    public function __construct(int $minimalReplacements)
    {
        $this->minimalReplacements = $minimalReplacements;
    }

    public function replaced(int $replaced): void
    {
        if ($replaced < $this->minimalReplacements) {
            throw ReplacementExpectationFailedException::insufficient($replaced, $this->minimalReplacements, 'at least');
        }
    }
}
