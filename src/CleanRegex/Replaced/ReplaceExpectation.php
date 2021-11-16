<?php
namespace TRegx\CleanRegex\Replaced;

interface ReplaceExpectation
{
    public function first(): ReplaceOperation;

    public function only(int $amount): ReplaceOperation;
}
