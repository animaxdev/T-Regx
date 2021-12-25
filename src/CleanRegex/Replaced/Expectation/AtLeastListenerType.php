<?php
namespace TRegx\CleanRegex\Replaced\Expectation;

class AtLeastListenerType implements ListenerType
{
    public function listener(int $limit): Listener
    {
        return new AtLeastListener($limit);
    }
}
