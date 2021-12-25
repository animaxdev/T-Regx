<?php
namespace TRegx\CleanRegex\Replaced\Preg;

use TRegx\CleanRegex\Internal\GroupKey\GroupKey;

class IndexedMatchAware
{
    /** @var Analyzed */
    private $analyzed;

    public function __construct(Analyzed $analyzed)
    {
        $this->analyzed = $analyzed;
    }

    public function matched(int $index, GroupKey $group): bool
    {
        $allMatchOffset = $this->analyzed->analyzedSubject();
        $stupidPhpMatch = $allMatchOffset[$group->nameOrIndex()][$index];
        if ($stupidPhpMatch === '') {
            return false;
        }
        return $stupidPhpMatch[1] > -1;
    }
}
