<?php namespace Bitcoind;

/**
 * Uses bcmath, which is bundled since PHP 4.0.4
 */
class Base58
{
    /**
     * @param string $num
     * @param string $alphabet
     * @return string
     */
    public static function encode($num, $alphabet)
    {
        $base = strlen($alphabet);
        $rep = '';

        while (true) {
            if (strlen($num) < 2) {
                if (intval($num) <= 0) {
                    break;
                }
            }
            $rem = bcmod($num, $base);
            $rep = $alphabet[intval($rem)] . $rep;
            $num = bcdiv(bcsub($num, $rem), $base);
        }
        return $rep;
    }

    /**
     * @param string $num
     * @param string $alphabet
     * @return string
     * @throws \Exception
     */
    public static function decode($num, $alphabet)
    {
        $base = strlen($alphabet);
        $dec = '0';

        $num_arr = str_split((string)$num);
        $cnt = strlen($num);
        for ($i=0; $i < $cnt; $i++) {
            $pos = strpos($alphabet, $num_arr[$i]);
            if ($pos === false) {
                throw new \Exception('Unknown character '.$num_arr[$i].' at offset '.$i);
            }
            $dec = bcadd(bcmul($dec, $base), $pos);
        }

        return self::bcDecHex($dec);
    }

    /**
     * @param string $dec
     * @return string
     */
    private static function bcDecHex($dec)
    {
        $last = bcmod($dec, 16);
        $remain = bcdiv(bcsub($dec, $last), 16);

        if ($remain == 0) {
            return dechex($last);
        }
        return self::bcDecHex($remain).dechex($last);
    }
}
