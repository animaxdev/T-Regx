<?php
namespace TRegx\CleanRegex\Internal\Number;

class PositiveNotation implements Notation
{
    /** @var string */
    private $number;

    public function __construct(string $number)
    {
        $this->number = $number;
    }

    public function integer(Base $base): int
    {
        if ($this->number === '') {
            throw new NumberFormatException();
        }
        if ($this->containsOnlyDigits($base)) {
            return $this->parseInteger($base);
        }
        throw new NumberFormatException();
    }

    private function containsOnlyDigits(Base $base): bool
    {
        return $this->containsOnly(\substr('0123456789abcdefghijklmnopqrstuvwxyz', 0, $base->base()));
    }

    private function containsOnly(string $digits): bool
    {
        return \rtrim(\strtolower($this->number), $digits) === '';
    }

    private function parseInteger(Base $base): int
    {
        $decimalString = \base_convert($this->number, $base->base(), 10);
        if (\filter_var($decimalString, \FILTER_VALIDATE_INT) === false) {
            throw new NumberOverflowException();
        }
        return $decimalString;
    }

    public function __toString(): string
    {
        return $this->number;
    }
}
