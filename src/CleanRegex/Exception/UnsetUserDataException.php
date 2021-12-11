<?php
namespace TRegx\CleanRegex\Exception;

use Exception;

class UnsetUserDataException extends Exception
{
    public function __construct()
    {
        parent::__construct("Expected to retrieve user data, but the user data wasn't set in the first place");
    }
}
