<?php
namespace Test\Feature\TRegx\CleanRegex\Match\Details\usingDuplicateName;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Exception\NonexistentGroupException;
use TRegx\CleanRegex\Match\Details\Detail;

class MatchDetailTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGet_text()
    {
        // given
        $detail = $this->detail();

        // when
        $declared = $detail->group('group');
        $parsed = $detail->usingDuplicateName()->group('group');

        // then
        $this->assertSame('One', $declared->text());
        $this->assertSame('Two', $parsed->text());
    }

    /**
     * @test
     */
    public function shouldGet_get()
    {
        // given
        $detail = $this->detail();

        // when
        $declared = $detail->get('group');
        $parsed = $detail->usingDuplicateName()->get('group');

        // then
        $this->assertSame('One', $declared);
        $this->assertSame('Two', $parsed);
    }

    /**
     * @test
     */
    public function shouldGetOffset()
    {
        // given
        $detail = $this->detail();

        // when
        $declared = $detail->group('group');
        $parsed = $detail->usingDuplicateName()->group('group');

        // then
        $this->assertSame(0, $declared->offset());
        $this->assertSame(3, $parsed->offset());
    }

    /**
     * @test
     */
    public function shouldGetName()
    {
        // given
        $detail = $this->detail();

        // when
        $declared = $detail->group('group');
        $parsed = $detail->usingDuplicateName()->group('group');

        // then
        $this->assertSame('group', $declared->name());
        $this->assertSame('group', $parsed->name());
    }

    /**
     * @test
     */
    public function shouldGetIndex()
    {
        // given
        $detail = $this->detail();

        // when
        $declared = $detail->group('group');

        // then
        $this->assertSame(1, $declared->index());
    }

    public function detail(): Detail
    {
        return pattern('(?<group>One)(?<group>Two)', 'J')
            ->match('OneTwo')
            ->stream()
            ->first();
    }

    /**
     * @test
     * @dataProvider methods
     * @param string $method
     */
    public function shouldThrow_group_InvalidGroupName(string $method)
    {
        // given
        $detail = $this->detail();

        // then
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Group name must be an alphanumeric string, not starting with a digit, but '!@#' given");

        // when
        $detail->usingDuplicateName()->$method('!@#');
    }

    /**
     * @test
     * @dataProvider methods
     * @param string $method
     */
    public function shouldThrow_group_IntegerGroup(string $method)
    {
        // given
        $detail = $this->detail();

        // then
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Group name must be an alphanumeric string, not starting with a digit, but '2' given");

        // when
        $detail->usingDuplicateName()->$method(2);
    }

    public function methods(): array
    {
        return [
            ['group'],
            ['get'],
            ['matched'],
        ];
    }

    /**
     * @test
     * @dataProvider methods
     * @param string $method
     */
    public function shouldThrowForMissingGroup(string $method)
    {
        // given
        $detail = $this->detail();

        // then
        $this->expectException(NonexistentGroupException::class);
        $this->expectExceptionMessage("Nonexistent group: 'missing'");

        // when
        $detail->usingDuplicateName()->$method('missing');
    }
}
