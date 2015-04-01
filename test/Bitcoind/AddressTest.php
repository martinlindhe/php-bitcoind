<?php

use Bitcoind\Address;

class BitcoinAddressTest extends \PHPUnit_Framework_TestCase
{
    function test1()
    {
        $this->assertEquals(false, Address::isValid(''));
    }

    function test2()
    {
        // lower case "i" is not valid
        $this->assertEquals(false, Address::isValid('invalid'));
    }

    function test3()
    {
        $this->assertEquals(true, Address::isValid('1AGNa15ZQXAZUgFiqJ2i7Z2DPU2J6hW62i'));
    }

    function test4()
    {
        $this->assertEquals(true, Address::isValid('1Q1pE5vPGEEMqRcVRMbtBK842Y6Pzo6nK9'));
    }

    function test5()
    {
        // checksum changed, original data
        $this->assertEquals(false, Address::isValid('1AGNa15ZQXAZUgFiqJ2i7Z2DPU2J6hW62X'));
    }

    function test6()
    {
        // data changed, original checksum
        $this->assertEquals(false, Address::isValid('1ANNa15ZQXAZUgFiqJ2i7Z2DPU2J6hW62i'));
    }

    function test7()
    {
        // invalid chars
        $this->assertEquals(false, Address::isValid('1A Na15ZQXAZUgFiqJ2i7Z2DPU2J6hW62i'));
    }
}
