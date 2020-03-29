<?php
namespace Test\Utils;

class PhpVersionDependent
{
    public static function getUnmatchedParenthesisMessage(int $offset): string
    {
        return "/(preg_match_all\(\): )?(Compilation failed: )?([Mm]issing|[Uu]nmatched) (closing parenthesis|parentheses|\)) at offset $offset/";
    }

    public static function getUnmatchedParenthesisMessage_ReplaceCallback(int $offset)
    {
        return "/(preg_replace_callback\(\): )?(Compilation failed: )?([mM]issing|[Uu]matched) (closing parenthesis|parentheses|\)) at offset $offset/";
    }

    public static function getAsymmetricQuantifierMessage(int $offset): string
    {
        if (PHP_VERSION_ID >= 70300) {
            return "Quantifier does not follow a repeatable item at offset $offset";
        }
        return "Nothing to repeat at offset $offset";
    }
}
