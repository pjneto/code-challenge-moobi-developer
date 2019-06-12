<?php

class ValuesUtil {

    public static function value_or_default(array $values, string $key, $default = null): string {
        return isset($values[$key]) ? $values[$key] : $default;
    }

    public static function is_null_or_empty(string $value): bool {
        return is_null($value) || strlen($value) === 0;
    }
}