<?php

namespace App\Support;

class NumberUtil
{
    /**
     * Clean price input (e.g., "1.000.000" -> 1000000).
     */
    public static function parseMoney(?string $value): ?float
    {
        if ($value === null || $value === '') {
            return null;
        }

        $digits = preg_replace('/[^\d]/', '', $value);
        return $digits !== '' ? (float) $digits : null;
    }
}
