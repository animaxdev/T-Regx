<?php
namespace TRegx\CleanRegex\Replaced\Callback;

use TRegx\CleanRegex\Replaced\Callback\Detail\Constituent\Constituents;
use TRegx\CleanRegex\Replaced\Callback\Detail\Constituent\LegacyModel;
use TRegx\CleanRegex\Replaced\Callback\Detail\Constituent\StandardModel;
use TRegx\CleanRegex\Replaced\Callback\Detail\DetailCallback;

class AbstractionCalled
{
    /** @var Constituents */
    private $constituents;
    /** @var DetailCallback */
    private $callback;

    public function __construct(Constituents $constituents, DetailCallback $callback)
    {
        $this->constituents = $constituents;
        $this->callback = $callback;
    }

    public function replaceStrings(LegacyModel $model, int $index): string
    {
        return $this->callback->replace($this->constituents->callbackString($model, $index), $index);
    }

    public function replaceOffsetArrays(StandardModel $model, int $index): string
    {
        return $this->callback->replace($this->constituents->callbackOffset($model, $index), $index);
    }
}
