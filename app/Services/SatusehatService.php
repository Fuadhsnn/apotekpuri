<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class SatusehatService
{
    protected $baseUrl;
    protected $clientId;
    protected $clientSecret;
    protected $organizationId;

    public function __construct()
    {
        $this->baseUrl = config('services.satusehat.base_url');
        $this->clientId = config('services.satusehat.client_id');
        $this->clientSecret = config('services.satusehat.client_secret');
        $this->organizationId = config('services.satusehat.organization_id');
    }

    /**
     * Mendapatkan token akses dari SATUSEHAT API
     *
     * @return string|null
     */
    public function getAccessToken()
    {
        // Cek apakah token sudah ada di cache
        if (Cache::has('satusehat_token')) {
            return Cache::get('satusehat_token');
        }

        try {
            $response = Http::asForm()->post("{$this->baseUrl}/oauth2/v1/accesstoken", [
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
                'grant_type' => 'client_credentials'
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $token = $data['access_token'];
                $expiresIn = $data['expires_in'] ?? 3600;

                // Simpan token ke cache dengan waktu kedaluwarsa
                Cache::put('satusehat_token', $token, now()->addSeconds($expiresIn - 60));

                return $token;
            }

            Log::error('SATUSEHAT Auth Error: ' . $response->body());
            return null;
        } catch (\Exception $e) {
            Log::error('SATUSEHAT Auth Exception: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Mendapatkan daftar obat dari SATUSEHAT API
     *
     * @return array|null
     */
    public function getMedicationList($searchTerm = null, $limit = 10)
    {
        $token = $this->getAccessToken();
        if (!$token) {
            return null;
        }

        try {
            $url = "{$this->baseUrl}/fhir-r4/v1/Medication";
            $query = ['_count' => $limit];
            
            if ($searchTerm) {
                $query['name:contains'] = $searchTerm;
            }

            $response = Http::withToken($token)
                ->withHeaders([
                    'Organization' => $this->organizationId
                ])
                ->get($url, $query);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('SATUSEHAT Medication List Error: ' . $response->body());
            return null;
        } catch (\Exception $e) {
            Log::error('SATUSEHAT Medication List Exception: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Mendapatkan detail obat dari SATUSEHAT API berdasarkan ID
     *
     * @param string $medicationId
     * @return array|null
     */
    public function getMedicationDetail($medicationId)
    {
        $token = $this->getAccessToken();
        if (!$token) {
            return null;
        }

        try {
            $url = "{$this->baseUrl}/fhir-r4/v1/Medication/{$medicationId}";

            $response = Http::withToken($token)
                ->withHeaders([
                    'Organization' => $this->organizationId
                ])
                ->get($url);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('SATUSEHAT Medication Detail Error: ' . $response->body());
            return null;
        } catch (\Exception $e) {
            Log::error('SATUSEHAT Medication Detail Exception: ' . $e->getMessage());
            return null;
        }
    }
}