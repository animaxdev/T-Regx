<?php
namespace TRegx\CleanRegex\Replaced;

use TRegx\CleanRegex\Exception\UnsetUserDataException;

class UserData
{
    /** @var bool */
    private $initialized = false;
    /** @var mixed */
    private $userData = null;

    public function set($userData): void
    {
        $this->initialized = true;
        $this->userData = $userData;
    }

    public function get()
    {
        if ($this->initialized) {
            return $this->userData;
        }
        throw new UnsetUserDataException();
    }
}
