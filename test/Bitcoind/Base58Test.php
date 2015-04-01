<?php

use Bitcoind\Base58;

class Base58Test extends \PHPUnit_Framework_TestCase
{
    function testEncode()
    {
        $this->assertEquals(
            '6E31Jz',
            Base58::encode(
                "3429289555",
                "123456789ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz"
            )
        );
    }

    function testDecode()
    {
        $this->assertEquals(
            '65a16059864a2fdbc7c99a4723a8395bc6f188ebc046b2ff',
            Base58::decode(
                '1AGNa15ZQXAZUgFiqJ2i7Z2DPU2J6hW62i',
                "123456789ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz"
            )
        );
    }
}
