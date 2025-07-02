<?php

use App\Http\SafeJsonResponse;
use App\Helpers\StringHelper;

if (!function_exists('safe_json_response')) {
    /**
     * Create a safe JSON response
     */
    function safe_json_response($data = [], $status = 200, $headers = [], $options = 0)
    {
        return new SafeJsonResponse($data, $status, $headers, $options);
    }
}

if (!function_exists('clean_utf8')) {
    /**
     * Clean UTF-8 string
     */
    function clean_utf8($string)
    {
        return StringHelper::cleanUtf8($string);
    }
}

if (!function_exists('safe_json_encode')) {
    /**
     * Safe JSON encode
     */
    function safe_json_encode($data, $options = 0)
    {
        return StringHelper::safeJsonEncode($data, $options);
    }
}