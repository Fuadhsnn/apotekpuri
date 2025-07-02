<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CleanJsonResponse
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);
        
        // Only process JSON responses
        if ($response instanceof JsonResponse) {
            try {
                // Get the original data
                $data = $response->getData(true);
                
                // Clean the data
                $cleanData = $this->cleanData($data);
                
                // Create new response with cleaned data
                $response->setData($cleanData);
                
            } catch (\Exception $e) {
                // If cleaning fails, return a safe error response
                return response()->json([
                    'error' => 'Response encoding error',
                    'message' => 'Data contains invalid characters'
                ], 500);
            }
        }
        
        return $response;
    }
    
    private function cleanData($data)
    {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $data[$key] = $this->cleanData($value);
            }
        } elseif (is_object($data)) {
            foreach ($data as $key => $value) {
                $data->$key = $this->cleanData($value);
            }
        } elseif (is_string($data)) {
            $data = $this->cleanString($data);
        }
        
        return $data;
    }
    
    private function cleanString($string)
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
        
        return $string;
    }
}