<?php
namespace TRegx\CleanRegex\Replaced;

trait ReplaceLimitHelpers
{
    public abstract function all(): ReplaceOperation;

    public function with(string $replacement): string
    {
        return $this->all()->with($replacement);
    }

    public function withReferences(string $replacement): string
    {
        return $this->all()->withReferences($replacement);
    }

    public function callback(callable $callback): string
    {
        return $this->all()->callback($callback);
    }

    public function withGroup($nameOrIndex): string
    {
        return $this->all()->withGroup($nameOrIndex);
    }

    public function byMap(array $occurrencesAndReplacements): string
    {
        return $this->all()->byMap($occurrencesAndReplacements);
    }

    public function byGroupMap($nameOrIndex, array $occurrencesAndReplacements): string
    {
        return $this->all()->byGroupMap($nameOrIndex, $occurrencesAndReplacements);
    }

    public function byGroupMapOrIgnore($nameOrIndex, array $occurrencesAndReplacements): string
    {
        return $this->all()->byGroupMapOrIgnore($nameOrIndex, $occurrencesAndReplacements);
    }

    public function byGroupMapOrEmpty($nameOrIndex, array $occurrencesAndReplacements): string
    {
        return $this->all()->byGroupMapOrEmpty($nameOrIndex, $occurrencesAndReplacements);
    }

    public function byGroupMapOrWith($nameOrIndex, array $occurrencesAndReplacements, string $substitute): string
    {
        return $this->all()->byGroupMapOrWith($nameOrIndex, $occurrencesAndReplacements, $substitute);
    }
}
