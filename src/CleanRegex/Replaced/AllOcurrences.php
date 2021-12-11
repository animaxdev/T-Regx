<?php
namespace TRegx\CleanRegex\Replaced;

class AllOcurrences
{
    /** @var array */
    private $matches;

    public function __construct(array $matches)
    {
        $this->matches = $matches;
    }

    public function all(): array
    {
        return \array_map(static function (array $match): string {
            return $match[0];
        }, $this->matches[0]);
    }
}
