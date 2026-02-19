<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;

class HorarioService
{
    protected string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('services.node_api.url');
    }

    public function getAll()
    {
        return Http::get("{$this->baseUrl}/horarios")->json();
    }

    public function search(array $filters)
    {
        if (!empty($filters['search'])) {
            return Http::get("{$this->baseUrl}/horarios/search", [
                'q' => $filters['search']
            ])->json();
        }
        return $this->getAll();
    }

    public function create(array $data)
    {
        $response = Http::post("{$this->baseUrl}/horarios", $data);
        if ($response->failed()) {
            throw ValidationException::withMessages($response->json()['errors'] ?? ['error' => 'Error en API']);
        }
        return $response->json();
    }

    public function update($id, array $data)
    {
        $response = Http::put("{$this->baseUrl}/horarios/{$id}", $data);
        if ($response->failed()) {
            throw ValidationException::withMessages($response->json()['errors'] ?? ['error' => 'Error en API']);
        }
        return $response->json();
    }

    public function delete($id)
    {
        return Http::delete("{$this->baseUrl}/horarios/{$id}")->json();
    }
}