<?php

namespace App\Services\AI;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OllamaService
{
    protected string $url;
    protected string $model;
    protected int $timeout;

    public function __construct()
    {
        $this->url = rtrim(config('services.ollama.url'), '/');
        $this->model = config('services.ollama.model');
        $this->timeout = (int) config('services.ollama.timeout', 60);
    }

    public function chat(array $messages, bool $json = false): array
    {
       $payload = [
    'model' => $this->model,
    'messages' => $messages,
    'stream' => false,
    'think' => false,
    'options' => [
    'num_predict' => 256,
],
];

        if ($json) {
            $payload['format'] = 'json';
        }

        Log::info('OLLAMA REQUEST', [
            'url' => $this->url . '/api/chat',
            'model' => $this->model,
            'payload' => $payload,
            'json_payload' => json_encode(
                $payload,
                JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
            ),
        ]);

        $response = Http::timeout($this->timeout)
            ->connectTimeout(10)
            ->acceptJson()
            ->asJson()
            ->post(
                $this->url . '/api/chat',
                $payload
            );

        Log::info('OLLAMA RESPONSE', [
            'status' => $response->status(),
            'body' => $response->body(),
        ]);

        $response->throw();

        return $response->json();
    }
}