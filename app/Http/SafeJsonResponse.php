<?php

namespace App\Http;

use Illuminate\Http\JsonResponse;
use App\Helpers\StringHelper;

class SafeJsonResponse extends JsonResponse
{
    /**
     * Set the data that should be JSON encoded.
     */
    public function setData($data = [])
    {
        // Clean data before encoding
        $cleanData = StringHelper::cleanData($data);
        
        // Use safe JSON encoding
        $this->data = StringHelper::safeJsonEncode($cleanData, $this->encodingOptions);

        if (! $this->hasValidJson(json_last_error())) {
            // If still fails, create a safe error response
            $this->data = json_encode([
                'error' => 'Data encoding error',
                'message' => 'Response contains invalid characters'
            ]);
        }

        return $this->update();
    }
}