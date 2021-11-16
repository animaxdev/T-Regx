<?php
namespace TRegx\CleanRegex\Replaced;

interface GroupReplacement
{
    public function with(string $replacement): string;

    public function empty(): string;

    public function ignore(): string;
}
