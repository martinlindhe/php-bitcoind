<?php

class BitcoindJsonRpcClientTest extends \PHPUnit_Framework_TestCase
{
    static function testGetInstance()
    {
        $bitcoind = new \Bitcoind\JsonRpcClient();

        $settingsTestFile = __DIR__.'/../settings/settings.php';
        require $settingsTestFile;

        return $bitcoind;
    }

    /**
     * @depends testGetInstance
     */
    function test1(\Bitcoind\JsonRpcClient $bitcoind)
    {
        // uses __call()
        $res = $bitcoind->getInfo();

        $this->assertEquals(1, $res->id);
        $this->assertEquals(200, $res->httpCode);
        $this->assertEquals(null, $res->error);

        $this->assertGreaterThanOrEqual(90200, $res->result->version);
        $this->assertGreaterThanOrEqual(70002, $res->result->protocolversion);
        $this->assertGreaterThanOrEqual(60000, $res->result->walletversion);
    }

    /**
     * @depends testGetInstance
     */
    function testGetBlock(\Bitcoind\JsonRpcClient $bitcoind)
    {
        // get block #1:
        $res = $bitcoind->getBlock('00000000839a8e6886ab5951d76f411475428afc90947ee320161bbf18eb6048');

        $this->assertEquals(2, $res->id);
        $this->assertEquals(200, $res->httpCode);
        $this->assertEquals(null, $res->error);

        $this->assertEquals(1231469665, $res->result->time);

        $this->assertEquals(215, $res->result->size);
        $this->assertEquals('00000000839a8e6886ab5951d76f411475428afc90947ee320161bbf18eb6048', $res->result->hash);
        $this->assertEquals('000000000019d6689c085ae165831e934ff763ae46a2a6c172b3f1b60a8ce26f', $res->result->previousblockhash);
        $this->assertEquals('000000006a625f06636b8bb6ac7b960a8d03705d1ace08b1a19da3fdcc99ddbd', $res->result->nextblockhash);
    }

    /**
     * @depends testGetInstance
     */
    function testGetRawTransaction(\Bitcoind\JsonRpcClient $bitcoind)
    {
        $res = $bitcoind->getRawTransaction('0e3e2357e806b6cdb1f70b54c3a3a17b6714ee1f0e68bebb44a74b1efd512098', 1);

        $this->assertEquals(3, $res->id);
        $this->assertEquals(200, $res->httpCode);
        $this->assertEquals(null, $res->error);

        $this->assertEquals('0e3e2357e806b6cdb1f70b54c3a3a17b6714ee1f0e68bebb44a74b1efd512098', $res->result->txid);
        $this->assertEquals('00000000839a8e6886ab5951d76f411475428afc90947ee320161bbf18eb6048', $res->result->blockhash);
        $this->assertEquals(1231469665, $res->result->blocktime);
        $this->assertEquals('01000000010000000000000000000000000000000000000000000000000000000000000000ffffffff0704ffff001d0104ffffffff0100f2052a0100000043410496b538e853519c726a2c91e61ec11600ae1390813a627c66fb8be7947be63c52da7589379515d4e0a604f8141781e62294721166bf621e73a82cbf2342c858eeac00000000', $res->result->hex);
    }

    /**
     * @depends testGetInstance
     */
    function testGetConnectionCount(\Bitcoind\JsonRpcClient $bitcoind)
    {
        $res = $bitcoind->getConnectionCount();

        $this->assertEquals(4, $res->id);
        $this->assertEquals(200, $res->httpCode);
        $this->assertEquals(null, $res->error);

        // NOTE: this test checks how many connections bitcoind has, and may vary
        $this->assertGreaterThanOrEqual(1, $res->result);
    }

    /**
     * @depends testGetInstance
     */
    function testGetGenerate(\Bitcoind\JsonRpcClient $bitcoind)
    {
        // NOTE: this test checks if bitcoind is configured to generate bitcoins
        $res = $bitcoind->getGenerate();

        $this->assertEquals(5, $res->id);
        $this->assertEquals(200, $res->httpCode);
        $this->assertEquals(null, $res->error);

        $this->assertInternalType('bool', $res->result);
        $this->assertSame(false, $res->result);
    }

    /**
     * @depends testGetInstance
     */
    function testGetPeerInfo(\Bitcoind\JsonRpcClient $bitcoind)
    {
        $res = $bitcoind->getPeerInfo();

        $this->assertEquals(6, $res->id);
        $this->assertEquals(200, $res->httpCode);
        $this->assertEquals(null, $res->error);

        $this->assertInternalType('array', $res->result);
    }


    /**
     * @depends testGetInstance
     */
    function testGetReceivedByAddress(\Bitcoind\JsonRpcClient $bitcoind)
    {
        $res = $bitcoind->getReceivedByAddress('12c6DSiU4Rq3P4ZxziKxzrL5LmMBrzjrJX');

        $this->assertEquals(7, $res->id);
        $this->assertEquals(200, $res->httpCode);
        $this->assertEquals(null, $res->error);

        $this->assertInternalType('float', $res->result);

        // XXX returns 0
        //var_dump($res);
        $this->assertEquals(0, $res->result);
    }

    /**
     * @depends testGetInstance
     */
    function testGetAccount(\Bitcoind\JsonRpcClient $bitcoind)
    {
        $res = $bitcoind->getAccount('12c6DSiU4Rq3P4ZxziKxzrL5LmMBrzjrJX');

        $this->assertEquals(8, $res->id);
        $this->assertEquals(200, $res->httpCode);
        $this->assertEquals(null, $res->error);

        $this->assertInternalType('string', $res->result);

        // XXX returns nothing
        //var_dump($res);
        $this->assertEquals('', $res->result);
    }

    /**
     * @depends testGetInstance
     */
    function testValidateAddress(\Bitcoind\JsonRpcClient $bitcoind)
    {
        $res = $bitcoind->validateAddress('12c6DSiU4Rq3P4ZxziKxzrL5LmMBrzjrJX');

        $this->assertEquals(9, $res->id);
        $this->assertEquals(200, $res->httpCode);
        $this->assertEquals(null, $res->error);

        $this->assertEquals(true, $res->result->isvalid);
        $this->assertEquals('12c6DSiU4Rq3P4ZxziKxzrL5LmMBrzjrJX', $res->result->address);
        $this->assertEquals(false, $res->result->ismine);
    }

    /**
     * @depends testGetInstance
     */
    function testIsValidAddress(\Bitcoind\JsonRpcClient $bitcoind)
    {
        $this->assertEquals(
            true,
            $bitcoind->isValidAddress('12c6DSiU4Rq3P4ZxziKxzrL5LmMBrzjrJX')
        );
    }


    /**
     * @expectedException \ConnectionRefusedException
     */
    function testInvalidConnect()
    {
        // verify that the correct exception is thrown on connection failure
        $bitcoind = new \Bitcoind\JsonRpcClient();
        $bitcoind->setPort(12345);

        $bitcoind->getInfo();
    }

    /**
     * @depends testGetInstance
     * @expectedException \MethodNotFoundException
     */
    function testInvalidMethod(\Bitcoind\JsonRpcClient $bitcoind)
    {
        $res = $bitcoind->nonExistingMethod();
        var_dump($res);
    }

    /**
     * @depends testGetInstance
     * @expectedException \AuthenticationFailureException
     */
    function testAuthFailure(\Bitcoind\JsonRpcClient $bitcoind)
    {
        // NOTE because this test changes the password, it needs to run last
        $oldPwd = $bitcoind->getPassword();
        $bitcoind->setPassword('bad');
        $res = $bitcoind->getInfo();
    }

}
