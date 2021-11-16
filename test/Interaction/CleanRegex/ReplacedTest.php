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
        $replace->byMap(['from' => 'to']);
        $replace->all()->byMap(['from' => 'to']);

        $replace->first()->byMap(['from' => 'to']);
        $replace->first()->byGroupMap('group', ['from' => 'to']);

        $replace->first()->withGroupOr('group')->with('new');
        $replace->first()->withGroupOr('group')->empty();
        $replace->first()->withGroupOr('group')->ignore();

        $replace->first()->byGroupMapOr('group', ['from' => 'to'])->with('');
        $replace->first()->byGroupMapOr('group', ['from' => 'to'])->empty();
        $replace->first()->byGroupMapOr('group', ['from' => 'to'])->ignore();

        $replace->only(2)->byMap(['from' => 'to']);

        // focus
        $replace->focus('asd')->withGroup('asd');
        $replace->focus('asd')->all()->callback(Functions::constant('asd'));
        $replace->focus('asd')->first()->callback(Functions::constant('asd'));
        $replace->focus('asd')->only(2)->callback(Functions::constant('asd'));

        $replace->focus('group')->exactly()->first()->with('new value');
    }
}
