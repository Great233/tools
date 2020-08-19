<?php

/**
 * Class Base62
 *
 * 十进制与62进制互相转换，依赖bcmath扩展
 * @author Great
 * @date 2020/08/19
 * @version 1.0
 */
class Base62 {

    private static $dict = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];

    private static function hasBcmath() {
        return extension_loaded('bcmath');
    }

    public static function to62(int $number): string {
        if (!self::hasBcmath()) {
            throw new RuntimeException('Missing bcmath extension.');
        }
        $result = '';
        while ($number > 0) {
            $remainder = bcmod($number, 62);
            $result = self::$dict[$remainder] . $result;
            $number = bcdiv($number, 62);
        }
        return $result;
    }

    public static function to10(string $base): int {
        if (!self::hasBcmath()) {
            throw new RuntimeException('Missing bcmath extension.');
        }
        $list = str_split($base);
        $length = count($list);
        $result = 0;
        for ($i = 0; $i < $length; $i++) {
            $result = bcadd(bcmul($result, 62), array_search($list[$i], self::$dict));
        }
        return $result;
    }
}
var_dump(Base62::to62(1243233));