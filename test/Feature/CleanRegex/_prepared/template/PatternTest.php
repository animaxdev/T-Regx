<?php
namespace Test\Feature\TRegx\CleanRegex\_prepared\template;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Test\Utils\AssertsPattern;
use TRegx\CleanRegex\Exception\ExplicitDelimiterRequiredException;
use TRegx\CleanRegex\Internal\Prepared\Figure\PlaceholderFigureException;
use TRegx\CleanRegex\Pattern;

/**
 * @coversNothing
 */
class PatternTest extends TestCase
{
    use AssertsPattern;

    /**
     * @test
     * @dataProvider templatesWithPlaceholder
     * @param string $pattern
     * @param string $expected
     */
    public function shouldUsePlaceholder(string $pattern, string $expected)
    {
        // when
        $pattern = Pattern::template($pattern)->literal('X')->build();

        // then
        $this->assertSamePattern($expected, $pattern);
    }

    public function templatesWithPlaceholder(): array
    {
        return [
            'standard'                               => ['You/her @ her?', '#You/her X her?#'],
            'comment (but no "x" flag)'              => ["You/her #@\n her?", "%You/her #X\n her?%"],
            'comment ("x" flag, but also "-x" flag)' => ["You/her (?x:(?-x:#@\n)) her?", "%You/her (?x:(?-x:#X\n)) her?%"],
        ];
    }

    /**
     * @test
     * @dataProvider templatesWithoutPlaceholders
     * @param string $pattern
     * @param string $expected
     */
    public function shouldNotMistakeLiteralForPlaceholder(string $pattern, string $expected)
    {
        // when
        $pattern = Pattern::template($pattern)->build();

        // then
        $this->assertSamePattern($expected, $pattern);
    }

    public function templatesWithoutPlaceholders(): array
    {
        return [
            "placeholder '@' in []"      => ['You/her [@] her?', '#You/her [@] her?#'],
            "placeholder '@' in \Q\E"    => ['You/her \Q@\E her?', '#You/her \Q@\E her?#'],
            "placeholder '@' escaped"    => ['You/her \@ her?', '#You/her \@ her?#'],
            "placeholder '@' in comment" => ["You/her (?x:#@\n) her?", "%You/her (?x:#@\n) her?%"],
            "placeholder '@' in control" => ["You/her \c@ her?", "#You/her \c@ her?#"],
        ];
    }

    /**
     * @test
     */
    public function shouldNotMistakePlaceholderInCommentInExtendedMode()
    {
        // when
        $pattern = Pattern::template("You/her #@\n her?", 'x')->build();

        // then
        $this->assertSamePattern("%You/her #@\n her?%x", $pattern);
    }

    /**
     * @test
     */
    public function shouldUsePlaceholderInCommentInExtendedMode_butExtendedModeIsSwitchedOff()
    {
        // when
        $pattern = Pattern::template("You/her (?-x:#@\n) her?", 'x')->literal('X')->build();

        // then
        $this->assertSamePattern("%You/her (?-x:#X\n) her?%x", $pattern);
    }

    /**
     * @test
     */
    public function shouldThrowForSuperfluousTemplateFigure()
    {
        // given
        $builder = Pattern::template('You/her, (are|is) @ (you|her)')
            ->literal('foo')
            ->literal('bar')
            ->literal('cat');

        // then
        $this->expectException(PlaceholderFigureException::class);
        $this->expectExceptionMessage("Found a superfluous figure: string ('bar'). Used 1 placeholders, but 3 figures supplied.");

        // when
        $builder->build();
    }

    /**
     * @test
     */
    public function shouldThrowForSuperfluousTemplateMask()
    {
        // given
        $builder = Pattern::template('Foo')->mask('foo', ['foo', 'bar']);

        // then
        $this->expectException(PlaceholderFigureException::class);
        $this->expectExceptionMessage("Found a superfluous figure: mask (2). Used 0 placeholders, but 1 figures supplied.");

        // when
        $builder->build();
    }

    /**
     * @test
     */
    public function shouldThrowForSuperfluousTemplateAlteration()
    {
        // given
        $builder = Pattern::template('Foo')->alteration(['foo', 'bar']);

        // then
        $this->expectException(PlaceholderFigureException::class);
        $this->expectExceptionMessage("Found a superfluous figure: array (2). Used 0 placeholders, but 1 figures supplied.");

        // when
        $builder->build();
    }

    /**
     * @test
     */
    public function shouldThrowForSuperfluousTemplatePattern()
    {
        // given
        $builder = Pattern::template('Foo')->pattern('bar');

        // then
        $this->expectException(PlaceholderFigureException::class);
        $this->expectExceptionMessage('Found a superfluous figure: pattern (bar). Used 0 placeholders, but 1 figures supplied.');

        // when
        $builder->build();
    }

    /**
     * @test
     */
    public function shouldThrowForRequiredExplicitDelimiter()
    {
        // given
        $builder = Pattern::template("s~i/e#++m%a!@*`_-;=,\1");

        // then
        $this->expectException(ExplicitDelimiterRequiredException::class);
        $this->expectExceptionMessage("Failed to select a distinct delimiter to enable template in its entirety");

        // when
        $builder->build();
    }

    /**
     * @test
     */
    public function shouldBuildTemplateWithPattern()
    {
        // when
        $pattern = Pattern::template('foo:@')->pattern('#https?/www%')->build();

        // then
        $this->assertSamePattern('~foo:#https?/www%~', $pattern);
        $this->assertConsumesFirst('foo:#http/www%', $pattern);
    }

    /**
     * @test
     */
    public function shouldMatchDelimiterPattern()
    {
        // when
        $pattern = Pattern::template('@')->pattern('/')->build();

        // then
        $this->assertSamePattern('#/#', $pattern);
        $this->assertConsumesFirst('/', $pattern);
    }

    /**
     * @test
     */
    public function shouldThrowForEmptyKeyword()
    {
        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Keyword cannot be empty, must consist of at least one character');

        // when
        Pattern::template('@')->mask('foo', ['' => 'Bar'])->build();
    }

    /**
     * @test
     */
    public function shouldParseUnicode()
    {
        // when
        $pattern = Pattern::template('ę')->build();

        // then
        $this->assertConsumesFirst('ę', $pattern);
    }

    /**
     * @test
     */
    public function shouldInjectAddPaddingToCommentBackslash()
    {
        // given
        $pattern = Pattern::inject('#\\', []);

        // when
        $valid = $pattern->valid();

        // then
        $this->assertFalse($valid);
    }
}
