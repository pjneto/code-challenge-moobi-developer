<?php

class ValuesUtil {

    public static function value_or_default(array $values, string $key, $default = null): string {
        return isset($values[$key]) ? $values[$key] : $default;
    }

    public static function is_null_or_empty(string $value): bool {
        return is_null($value) || strlen($value) === 0;
    }

    public static function format_money(float $value, bool $signal = true) {
        return (boolval($signal) ? "R$ " : "") . number_format($value, 2, ",", ".");
    }

    public static function format_date(string $value = null): string {
        if (is_null($value)) {
            $value = time();
        }
        return date('Y-m-d H:i:s', $value);
    }

    public static function show_date(string $date, bool $time = false): string {
        $format = "d/m/Y";
        $format .= boolval($time) ? " H:m:s" : "";
        
        return date($format, strtotime($date));
    }
}