<?php

namespace App\Helpers;

class StringHelper
{
    /**
     * Clean string from malformed UTF-8 characters
     */
    public static function cleanUtf8($string)
    {
        if ($string === null) {
            return null;
        }
        
        // Convert to string
        $string = (string) $string;
        
        // Remove BOM
        $string = str_replace("\xEF\xBB\xBF", '', $string);
        
        // Fix encoding
        $string = mb_convert_encoding($string, 'UTF-8', 'UTF-8');
        
        // Remove control characters
        $string = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $string);
        
        // Ensure valid UTF-8
        if (!mb_check_encoding($string, 'UTF-8')) {
            $string = mb_convert_encoding($string, 'UTF-8', 'auto');
        }
        
        // Last resort: ASCII only
        if (!mb_check_encoding($string, 'UTF-8')) {
            $string = preg_replace('/[^\x20-\x7E]/', '', $string);
        }
        
        return trim($string);
    }

    /**
     * Clean array/object recursively
     */
    public static function cleanData($data)
    {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $data[$key] = self::cleanData($value);
            }
        } elseif (is_object($data)) {
            foreach ($data as $key => $value) {
                $data->$key = self::cleanData($value);
            }
        } elseif (is_string($data)) {
            $data = self::cleanUtf8($data);
        }
        
        return $data;
    }

    /**
     * Safe JSON encode
     */
    public static function safeJsonEncode($data, $options = 0)
    {
        // Clean data first
        $cleanData = self::cleanData($data);
        
        // Try to encode
        $json = json_encode($cleanData, $options | JSON_UNESCAPED_UNICODE);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            // If still fails, use ASCII safe encoding
            $json = json_encode($cleanData, $options);
        }
        
        return $json;
    }
}