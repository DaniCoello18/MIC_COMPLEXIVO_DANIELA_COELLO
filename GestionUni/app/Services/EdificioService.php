<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;

class EdificioService
{
    protected string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('services.node_api.url');
    }

    public function getAll()
    {
        return Http::get("{$this->baseUrl}/edificios")->json();
    }

    // Búsqueda corregida con parámetro 'q'
    public function search(array $filters)
    {
        if (!empty($filters['search'])) {
            return Http::get("{$this->baseUrl}/edificios/search", [
                'q' => $filters['search']
            ])->json();
        }
        return $this->getAll();
    }

    public function create(array $data)
    {
        $response = Http::post("{$this->baseUrl}/edificios", $data);
        if ($response->failed()) {
            throw ValidationException::withMessages($response->json()['errors'] ?? ['error' => 'Error en API']);
        }
        return $response->json();
    }

    public function update($id, array $data)
    {
        $response = Http::put("{$this->baseUrl}/edificios/{$id}", $data);
        if ($response->failed()) {
            throw ValidationException::withMessages($response->json()['errors'] ?? ['error' => 'Error en API']);
        }
        return $response->json();
    }

    public function delete($id)
    {
        return Http::delete("{$this->baseUrl}/edificios/{$id}")->json();
    }
}