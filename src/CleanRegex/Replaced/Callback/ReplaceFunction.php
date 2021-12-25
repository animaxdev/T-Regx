<?php
namespace TRegx\CleanRegex\Replaced\Callback;

use TRegx\CleanRegex\Exception\InvalidReplacementException;
use TRegx\CleanRegex\Internal\Type\ValueType;
use TRegx\CleanRegex\Replaced\Callback\Detail\ReplaceDetail;

class ReplaceFunction
{
    /** @var callable */
    private $function;

    public function __construct(callable $function)
    {
        $this->function = $function;
    }

    public function apply(ReplaceDetail $argument): string
    {
        $replacement = ($this->function)($argument);
        if (\is_string($replacement)) {
            return $replacement;
        }
        throw new InvalidReplacementException(new ValueType($replacement));
    }
}
