<?php
namespace TRegx\CleanRegex\Replaced;

class AtLeastListenerFactory implements ListenerFactory
{
    public function create(int $limit): Listener
    {
        return new AtLeastListener($limit);
    }
}
