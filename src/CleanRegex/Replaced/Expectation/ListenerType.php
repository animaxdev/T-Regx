<?php
namespace TRegx\CleanRegex\Replaced\Expectation;

interface ListenerType
{
    public function listener(int $limit): Listener;
}
