<?php

namespace ShInUeXx\Generic;

use GMP;
use InvalidArgumentException;
use function substr, strrev, ord, strlen, chr, current, unpack, pack, gmp_init, gmp_or, gmp_mul, gmp_div, gmp_add, gmp_intval;
use const PHP_INT_SIZE;

final class BitConverter
{
    private const unpack = [
        4 => ['f', 'L'],
        8 => ['d', 'Q']
    ];

    private function __construct()
    {
    }

    private function __clone()
    {
    }

    static public function BytesToInt(string $raw, int $size, int $offset = 0, bool $isBigEndian = true): int
    {
        if ($size > PHP_INT_SIZE) throw new InvalidArgumentException(sprintf('PHP_INT_SIZE(%d) is smaller than $size(%d)', PHP_INT_SIZE, $size));
        $input = substr($raw, $offset, $size);
        if (!$isBigEndian) $input = strrev($input);
        $out = 0;
        $l = strlen($input);
        for ($i = 0; $i < $l; ++$i) {
            $out = ($out << 8) | ord($input[$i]);
        }
        return $out;
    }

    static public function IntToBytes(int $value, int $size, bool $isBigEndian = true): string
    {
        $raw = '';
        while ($size--) {
            $raw .= chr($value & 0xff);
            $value >>= 8;
        }
        if ($isBigEndian) $raw = strrev($raw);
        return $raw;
    }

    static private function i2f(int $value, int $size): float
    {
        list($f, $i) = self::unpack[$size];
        return current(unpack($f, pack($i, $value)));
    }

    static private function f2i(float $value, int $size): int
    {
        list($f, $i) = self::unpack[$size];
        return current(unpack($i, pack($f, $value)));
    }

    static public function BytesToFloat(string $raw, bool $isDouble, int $offset = 0, bool $isBigEndian = true): float
    {
        $size = $isDouble ? 8 : 4;
        $input = self::BytesToInt($raw, $size, $offset, $isBigEndian);
        return self::i2f($input, $size);
    }

    static public function FloatToBytes(float $value, bool $isDouble, bool $isBigEndian = true): string
    {
        $size = $isDouble ? 8 : 4;
        $int = self::f2i($value, $size);
        return self::IntToBytes($int, $size, $isBigEndian);
    }

    static public function RawToGMP(string $raw, int $size, int $offset = 0, bool $isBigEndian = true): GMP
    {
        $input = substr($raw, $offset, $size);
        if (!$isBigEndian) $input = strrev($input);
        $gmp = gmp_init(0);
        $l = strlen($input);
        for ($i = 0; $i < $l; ++$i) {
            $gmp = gmp_or(gmp_mul($gmp, 256), ord($input[$i]));
        }
        return $gmp;
    }

    static public function GMPToRaw(GMP $value, int $size, bool $isBigEndian = true): string
    {
        $raw = '';
        while ($size--) {
            $int = gmp_intval(gmp_add($value, 0xff));
            $raw .= chr($int);
            $value = gmp_div($value, 256);
        }
        if ($isBigEndian) $raw = strrev($raw);
        return $raw;
    }

    static public function ToHexString(string $raw): string
    {
        return current(unpack('h*', $raw));
    }

    static public function FromHexString(string $hex): string
    {
        return pack("h*", $hex);
    }
}
