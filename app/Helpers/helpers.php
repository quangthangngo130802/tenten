<?php

use App\Models\Config;

if (!function_exists('vat_rate')) {
    function vat_rate()
    {
        $vat = Config::first();
        $value = $vat->vat_rate ?? 0;

        // Nếu là số thập phân .00 thì bỏ phần thập phân
        if (is_numeric($value) && floor($value) == $value) {
            return (int) $value;
        }

        return $value;
    }
}

if (!function_exists('vat_amount')) {
    /**
     * Tính số tiền VAT từ số tiền gốc.
     *
     * @param float|int $amount
     * @return float
     */
    function vat_amount($amount)
    {
        $vatPercent = vat_rate();
        return round($amount * $vatPercent / 100, 2);
    }
}

if (!function_exists('price_with_vat')) {
    /**
     * Tính tổng tiền sau khi cộng VAT
     *
     * @param float|int $amount Số tiền gốc
     * @return float Tổng tiền sau thuế
     */
    function price_with_vat($amount)
    {
        $vatPercent = vat_rate(); // ví dụ 10 (hoặc 10.5)

        return round($amount * (1 + $vatPercent / 100), 2); // Làm tròn 2 chữ số
    }
}
