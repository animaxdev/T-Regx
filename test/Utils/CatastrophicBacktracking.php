<?php
namespace Test\Utils;

trait CatastrophicBacktracking
{
    /**
     * @return string[]
     */
    public function catastrophicBacktracking(): array
    {
        /**
         * This pattern and subject are deliberately created to
         * produce {@see CatastrophicBacktrackingException}, if they
         * are called more than once. That way, we can test
         * whether "first" method really tries to search the first
         * occurrence.
         */
        return ['(([a\d]+[a\d]+)+3)', '123 123 aaaaaaaaaaaaaaaaaaaa 3'];
    }
}
