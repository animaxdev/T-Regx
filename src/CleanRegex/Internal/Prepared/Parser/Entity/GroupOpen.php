<?php
namespace TRegx\CleanRegex\Internal\Prepared\Parser\Entity;

class GroupOpen implements Entity
{
    use TransitiveFlags, PatternEntity;

    public function pattern(): string
    {
        return '(';
    }
}
