<?php
namespace TRegx\CleanRegex\Replaced;

class ReplacerCallback
{
    /** @var CalledBack */
    private $calledBack;

    public function __construct(CalledBack $calledBack)
    {
        $this->calledBack = $calledBack;
    }

    public function replaced(ReplacementFunction $function): string
    {
        return $this->calledBack->replaced(static function (array $matches) use ($function): string {
            return $function->apply($matches[0]);
        });
    }
}
