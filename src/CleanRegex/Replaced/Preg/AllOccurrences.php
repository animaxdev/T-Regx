<?php
namespace TRegx\CleanRegex\Replaced\Preg;

class AllOccurrences
{
    /** @var Analyzed */
    private $analyzed;

    public function __construct(Analyzed $analyzed)
    {
        $this->analyzed = $analyzed;
    }

    public function all(): array
    {
        $analyzed = $this->analyzed->analyzedSubject();
        return \array_map(static function (array $match): string {
            return $match[0];
        }, $analyzed[0]);
    }
}
