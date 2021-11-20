<?php
namespace TRegx\CleanRegex\Replaced;

use TRegx\CleanRegex\Exception\InvalidReplacementException;
use TRegx\CleanRegex\Internal\Type\ValueType;

class ReplacementFunction
{
    /** @var callable */
    private $function;

    public function __construct(callable $function)
    {
        $this->function = $function;
    }

    public function apply($argument): string
    {
        $replacement = ($this->function)($argument);
        if (\is_string($replacement)) {
            return $replacement;
        }
        throw new InvalidReplacementException(new ValueType($replacement));
    }
}
