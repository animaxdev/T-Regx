<?php
namespace TRegx\CleanRegex\Replaced;

interface ListenerFactory
{
    public function create(int $limit): Listener;
}
