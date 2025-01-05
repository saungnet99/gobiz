<?php

use App\Currency;
use Illuminate\Support\Facades\DB;

if (!function_exists('currency')) {
    function currency($amount)
    {
        // Fetch currencies and configuration
        $currencies = Currency::get();
        $config = DB::table('config')->get();
        $setCurrencyConfig = $config[1]->config_value;

        // Initialize variables
        $setCurrency = null;
        $symbolFirst = null;

        // Find the matching currency
        foreach ($currencies as $currency) {
            if ($currency->iso_code === $setCurrencyConfig) {
                $setCurrency = $currency->symbol;
                $symbolFirst = $currency->symbol_first;
                break;
            }
        }

        // Format currency based on symbol position
        if ($symbolFirst === "false") { // Right-to-Left locales
            return number_format($amount, 2) . $setCurrency;
        } else { // Left-to-Right locales
            return $setCurrency . number_format($amount, 2);
        }
    }
}
