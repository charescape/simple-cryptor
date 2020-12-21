<?php

declare(strict_types=1);

namespace Charescape\SimpleCryptor;

class SimpleCryptor
{
    public static function encrypt(string $origin, string $algo, bool $is_algo_encoded = false): string
    {
        $encrypted = $origin;

        if ($is_algo_encoded) {
            $algo = base64_decode(str_replace(['__p__', '__s__', '__e__'], ['+', '/', '='], $algo));
        }

        $rules = explode(',', $algo);
        foreach ($rules as $rule) {
            $rule = explode(':', $rule);

            if ($rule[0] === 'i') {
                $encrypted = substr_replace($encrypted, self::generate_random_char((int)$rule[2]), ((int)$rule[1]), 0);
            } elseif ($rule[0] === 'r') {
                $encrypted = strrev($encrypted);
            }
        }

        return $encrypted;
    }

    public static function decrypt(string $encrypted, string $algo, bool $is_algo_encoded = false): string
    {
        $origin = $encrypted;

        if ($is_algo_encoded) {
            $algo = base64_decode(str_replace(['__p__', '__s__', '__e__'], ['+', '/', '='], $algo));
        }

        $rules = array_reverse(explode(',', $algo));
        foreach ($rules as $rule) {
            $rule = explode(':', $rule);

            if ($rule[0] === 'i') {
                $origin = substr_replace($origin, '', ((int)$rule[1]), ((int)$rule[2]));
            } elseif ($rule[0] === 'r') {
                $origin = strrev($origin);
            }
        }

        return $origin;
    }

    private static function generate_random_char(int $len): string
    {
        $alphabet = 'qwertyuiopasdfghjklzxcvbnm';

        return substr(str_shuffle($alphabet . $alphabet), 0, $len);
    }
}
