<?php
namespace TRegx\CleanRegex\Replaced;

interface Listener
{
    public function replaced(int $replaced): void;
}
