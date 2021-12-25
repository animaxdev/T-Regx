<?php
namespace TRegx\CleanRegex\Replaced\Expectation;

interface Listener
{
    public function replaced(int $replaced): void;
}
