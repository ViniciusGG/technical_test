<?php

namespace App\Services\ExternalService;

use Illuminate\Support\Facades\Http;


class PipedreamService
{
    private string $url;

    private string $email;

    public function __construct()
    {
        $this->url = config('settings.endpoint_pipedream');
        $this->email = config('settings.email_pipedream');
    }


    public function authorizeTransaction(array $data): bool
    {
        $token = base64_encode($this->email);
        try {
            $response = Http::post($this->url, $data, [
                'Authorization' => 'Bearer ' . $token
            ]);
        } catch (\Exception $e) {
            return false;
        }
        return $response->json()['authorized'];
    }
}
