<?php
namespace TRegx\CleanRegex\Replaced;

interface ReplaceOperation
{
    public function with(string $replacement): string;

    public function withReferences(string $replacement): string;

    public function callback(callable $replacer): string;

    public function withGroup($nameOrIndex): string;

    public function withGroupOrIgnore($nameOrIndex): string;

    public function withGroupOrEmpty($nameOrIndex): string;

    public function byMap(array $occurrencesAndReplacements): string;

    public function withGroupOr($nameOrIndex): GroupReplacement;

    public function byGroupMap($nameOrIndex, array $occurrencesAndReplacements): string;

    public function byGroupMapOr($nameOrIndex, array $occurrencesAndReplacements): GroupReplacement;

    public function byGroupMapOrIgnore($nameOrIndex, array $occurrencesAndReplacements): string;

    public function byGroupMapOrEmpty($nameOrIndex, array $occurrencesAndReplacements): string;

    public function byGroupMapOrWith($nameOrIndex, array $occurrencesAndReplacements, string $substitute): string;
}
