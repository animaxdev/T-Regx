<?php
namespace Test\Feature\CleanRegex\Replaced\callback\Details\getUserData;

use PHPUnit\Framework\TestCase;
use Test\Utils\ExactExceptionMessage;
use TRegx\CleanRegex\Exception\UnsetUserDataException;
use TRegx\CleanRegex\Match\Details\Detail;

class Test extends TestCase
{
    use ExactExceptionMessage;

    /**
     * @test
     */
    public function shouldSetAndGetUserData()
    {
        // given
        pattern('Foo')
            ->replaced('Foo')
            ->callback(function (Detail $detail) {
                $detail->setUserData('Bar');
                $detail->setUserData('Cat');
                $this->assertSame('Cat', $detail->getUserData());
                $this->assertSame('Cat', $detail->getUserData());

                // cleanup
                return '';
            });
    }

    /**
     * @test
     */
    public function shouldSetAndGetUserData_False()
    {
        // given
        pattern('Foo')
            ->replaced('Foo')
            ->callback(function (Detail $detail) {
                $detail->setUserData(false);
                $this->assertFalse($detail->getUserData());

                // cleanup
                return '';
            });
    }

    /**
     * @test
     */
    public function shouldSetAndGetUserData_Null()
    {
        // given
        pattern('Foo')
            ->replaced('Foo')
            ->callback(function (Detail $detail) {
                $detail->setUserData(null);
                $this->assertNull($detail->getUserData());

                // cleanup
                return '';
            });
    }

    /**
     * @test
     */
    public function shouldThrowForUnassignedUserData()
    {
        // then
        $this->expectException(UnsetUserDataException::class);
        $this->expectExceptionMessage("Expected to retrieve user data, but the user data wasn't set in the first place");

        // given
        pattern('Foo')->replaced('Foo')->callback(function (Detail $detail) {
            $detail->getUserData();
        });
    }

    /**
     * @test
     */
    public function shouldThrowForUnassignedUserDataTwice()
    {
        // then
        $this->expectException(UnsetUserDataException::class);
        $this->expectExceptionMessage("Expected to retrieve user data, but the user data wasn't set in the first place");

        // given
        pattern('Foo')->replaced('Foo')->callback(function (Detail $detail) {
            try {
                $detail->getUserData();
            } catch (UnsetUserDataException $ignored) {
            }
            $detail->getUserData();
        });
    }
}
