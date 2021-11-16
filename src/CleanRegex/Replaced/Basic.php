<?php
namespace TRegx\CleanRegex\Replaced;

interface Basic extends ReplaceOperation
{
    public function all(): ReplaceOperation;

    public function first(): ReplaceOperation;

    public function only(int $amount): ReplaceOperation;

    public function exactly(): ReplaceExpectation;

    public function atMost(): ReplaceExpectation;

    public function atLeast(): ReplaceExpectation;
}
