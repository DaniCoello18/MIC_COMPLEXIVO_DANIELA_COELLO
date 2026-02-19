<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;

class ProfesorService
{
    protected string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('services.node_api.url');
    }

    public function getAll()
    {
        return Http::get("{$this->baseUrl}/profesores")->json();
    }

    // Filtro corregido para usar un solo parámetro 'q'
    public function search(array $filters)
    {
        if (!empty($filters['search'])) {
            return Http::get("{$this->baseUrl}/profesores/search", [
                'q' => $filters['search']
            ])->json();
        }
        return $this->getAll();
    }

    public function create(array $data)
    {
        $response = Http::post("{$this->baseUrl}/profesores", $data);
        if ($response->failed()) {
            throw ValidationException::withMessages($response->json()['errors'] ?? ['error' => 'Error en API']);
        }
        return $response->json();
    }

    public function update($id, array $data)
    {
        $response = Http::put("{$this->baseUrl}/profesores/{$id}", $data);
        if ($response->failed()) {
            throw ValidationException::withMessages($response->json()['errors'] ?? ['error' => 'Error en API']);
        }
        return $response->json();
    }

    public function delete($id)
    {
        return Http::delete("{$this->baseUrl}/profesores/{$id}")->json();
    }
}