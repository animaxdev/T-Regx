<?php
namespace TRegx\CleanRegex\Replaced\Callback;

interface TextCallback
{
    public function replace(string $text): string;
}
