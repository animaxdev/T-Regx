<?php
namespace Test\Interaction\TRegx\CleanRegex;

use Test\Utils\Functions;

class ReplacedTest
{
    /**
     * @test
     */
    public function should()
    {
        $replace->focus('asd')->withGroup('asd');
        $replace->focus('asd')->all()->callback(Functions::constant('asd'));
        $replace->focus('asd')->first()->callback(Functions::constant('asd'));
        $replace->focus('asd')->only(2)->callback(Functions::constant('asd'));
        $replace->focus('group')->exactly()->first()->with('new value');
    }
}
