<?php
namespace Test\Utils;

use TRegx\CleanRegex\Match\Details\Detail;
use TRegx\CleanRegex\Match\Details\Group\Group;

class DetailFunctions
{
    public static function text(): callable
    {
        return static function (Detail $detail): string {
            return $detail->text();
        };
    }

    public static function equals(string $detail): callable
    {
        return function (Detail $match) use ($detail) {
            return "$match" === $detail;
        };
    }

    public static function notEquals(string $detail): callable
    {
        return function ($match) use ($detail) {
            if ($match instanceof Detail || $match instanceof Group) {
                return "$match" !== $detail;
            }
            throw new \Exception();
        };
    }

    public static function indexNotEquals(int $index): callable
    {
        return function (Detail $detail) use ($index) {
            return $detail->index() !== $index;
        };
    }

    public static function collect(?array &$details, $return = null): callable
    {
        return function (Detail $detail) use (&$details, $return) {
            $details[$detail->index()] = $detail->text();
            return $return;
        };
    }

    public static function collecting(?array &$details, callable $return = null): callable
    {
        return function (Detail $detail) use (&$details, $return) {
            $details[$detail->index()] = $detail->text();
            if ($return !== null) {
                return $return($detail);
            }
            return null;
        };
    }
}
