<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    protected $apiUrl;
    protected $apiKey;
    protected $instance;

    public function __construct()
    {
        $this->apiUrl = rtrim(env('EVOLUTION_API_URL', 'http://localhost:8080'), '/');
        $this->apiKey = env('EVOLUTION_API_KEY');
        $this->instance = env('EVOLUTION_API_INSTANCE', 'tallerpro');
    }

    /**
     * Envía un mensaje de texto a un número específico a través de Evolution API.
     *
     * @param string $phone Número de teléfono con código de país (ej: 5917xxxxxx)
     * @param string $message Mensaje a enviar
     * @return array|null
     */
    public function sendMessage(string $phone, string $message)
    {
        $url = "{$this->apiUrl}/message/sendText/{$this->instance}";

        $response = Http::withHeaders([
            'apikey' => $this->apiKey,
            'Content-Type' => 'application/json',
        ])->post($url, [
            'number' => $phone,
            'text' => $message,
            'options' => [
                'delay' => 1200, // Simula que está escribiendo por 1.2 segundos
                'presence' => 'composing',
            ]
        ]);

        if ($response->successful()) {
            return $response->json();
        }

        Log::error("Error enviando WhatsApp a {$phone}. Status: {$response->status()} - Response: " . $response->body());
        return null;
    }

    public function getConnectionState()
    {
        $url = "{$this->apiUrl}/instance/connectionState/{$this->instance}";
        $response = Http::withHeaders(['apikey' => $this->apiKey])->get($url);
        return $response->successful() ? $response->json() : null;
    }

    public function getConnectQr()
    {
        $url = "{$this->apiUrl}/instance/connect/{$this->instance}";
        $response = Http::withHeaders(['apikey' => $this->apiKey])->get($url);
        return $response->successful() ? $response->json() : null;
    }

    public function logout()
    {
        $url = "{$this->apiUrl}/instance/logout/{$this->instance}";
        $response = Http::withHeaders(['apikey' => $this->apiKey])->delete($url);
        return $response->successful();
    }
}
