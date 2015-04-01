<?php namespace Bitcoind;

/**
 * A Bitcoin address is a base58 encoded string of 25 bytes
 * http://rosettacode.org/wiki/Bitcoin/address_validation
 */
class Address
{
    public static $alphabet = "123456789ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz";

    /**
     * @param string $s
     * @return bool
     */
    public static function isValid($s)
    {
        if (!$s) {
            return false;
        }

        if (preg_match('/[^1-9A-HJ-NP-Za-km-z]/', $s)) {
            return false;
        }

        $data = self::decode($s);

        if (strlen($data) != 25*2) {
            // wrong length
            return false;
        }

        $version = substr($data, 0, 1*2);
        if ($version != "00") {
            return false;
        }

        // 20 bytes of RIPEMD-160 digest
        $ripemd = substr($data, 2, 20*2);

        $checksum = substr($data, 21*2, 4*2);

        // verify checksum
        $packed = pack("H*", $version.$ripemd);
        $calced = hash("sha256", hash("sha256", $packed, true));
        $calced = substr($calced, 0, 8);

        if ($checksum != $calced) {
            return false;
        }

        return true;
    }

    public static function encode($num)
    {
        return Base58::encode($num, self::$alphabet);
    }

    /**
     * @param string $num
     * @return string exactly 25 bytes, as lower case hex
     */
    public static function decode($num)
    {
        $res = Base58::decode($num, self::$alphabet);

        return str_pad($res, 50, '0', STR_PAD_LEFT);
    }
}
