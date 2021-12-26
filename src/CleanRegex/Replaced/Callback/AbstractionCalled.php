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

    public function replaceLegacy(LegacyModel $model, int $index): string
    {
        return $this->callback->replace($this->constituents->legacyConstituent($model, $index), $index);
    }

    public function replaceStandard(StandardModel $model, int $index): string
    {
        return $this->callback->replace($this->constituents->standardConstituent($model, $index), $index);
    }
}
