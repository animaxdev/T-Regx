<?php
namespace TRegx\SafeRegex\Exception\Factory;

use TRegx\CleanRegex\Exception\CleanRegex\MalformedPatternException;
use TRegx\SafeRegex\Constants\PhpErrorConstants;
use TRegx\SafeRegex\Exception\CompileSafeRegexException;
use TRegx\SafeRegex\PhpError;

class CompileSafeRegexExceptionFactory
{
    /** @var PhpErrorConstants */
    private $phpErrorConstants;
    /** @var string */
    private $methodName;
    /** @var PhpError */
    private $error;

    public function __construct(string $methodName, PhpError $error)
    {
        $this->phpErrorConstants = new PhpErrorConstants();
        $this->methodName = $methodName;
        $this->error = $error;
    }

    public function create(): CompileSafeRegexException
    {
        [$class, $message] = $this->exceptionClassAndMessage($this->error->getMessage());
        return new $class(
            $this->methodName,
            $message,
            $this->error,
            $this->phpErrorConstants->getConstant($this->error->getType()));
    }

    private function exceptionClassAndMessage(string $message): array
    {
        if ($this->matchMalformed($message, $result)) {
            return [MalformedPatternException::class, $result];
        }
        return [CompileSafeRegexException::class, $message];
    }

    private function matchMalformed(string $message, ?string &$result): bool
    {
        $pattern = '/^preg_(?:match(?:_all)?|replace(?:_callback(?:_array)?)?|filter|split|grep)\(\): (?:Compilation failed: )?(.*)/';
        if (\preg_match($pattern, $message, $match) === 1) {
            $result = \ucfirst($match[1]);
            return true;
        }
        return false;
    }
}
