<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;

class MatriculaService
{
    protected string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('services.node_api.url');
    }

    public function getAll()
    {
        return Http::get("{$this->baseUrl}/matriculas")->json();
    }

    public function search(array $filters)
    {
        if (!empty($filters['search'])) {
            return Http::get("{$this->baseUrl}/matriculas/search", [
                'q' => $filters['search']
            ])->json();
        }
        return $this->getAll();
    }

    public function create(array $data)
    {
        $response = Http::post("{$this->baseUrl}/matriculas", $data);
        if ($response->failed()) {
            throw ValidationException::withMessages($response->json()['errors'] ?? ['error' => 'Error al crear matrícula']);
        }
        return $response->json();
    }

    public function update($id, array $data)
    {
        $response = Http::put("{$this->baseUrl}/matriculas/{$id}", $data);
        if ($response->failed()) {
            throw ValidationException::withMessages($response->json()['errors'] ?? ['error' => 'Error al actualizar matrícula']);
        }
        return $response->json();
    }

    public function delete($id)
    {
        return Http::delete("{$this->baseUrl}/matriculas/{$id}")->json();
    }
}