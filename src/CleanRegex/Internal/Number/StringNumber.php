<?php
namespace TRegx\CleanRegex\Internal\Number;

class StringNumber
{
    /** @var string */
    private $number;

    public function __construct(string $number)
    {
        $this->number = $number;
    }

    public function asInt(Base $base): int
    {
        return $this->notation($this->number)->integer($base);
    }

    private function notation(string $value): Notation
    {
        if ($value === '' || $value[0] !== '-') {
            return new PositiveNotation($value);
        }
        return new NegativeNotation(new PositiveNotation(\substr($value, 1)));
    }
}
