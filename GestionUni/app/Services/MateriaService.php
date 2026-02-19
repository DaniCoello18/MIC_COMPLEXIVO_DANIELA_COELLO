<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;

class MateriaService
{
    protected string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('services.node_api.url');
    }

    public function getAll()
    {
        return Http::get("{$this->baseUrl}/materias")->json();
    }

    // Corregido para usar el parámetro 'q'
    public function search(array $filters)
    {
        if (!empty($filters['search'])) {
            return Http::get("{$this->baseUrl}/materias/search", [
                'q' => $filters['search']
            ])->json();
        }
        return $this->getAll();
    }

    public function create(array $data)
    {
        $response = Http::post("{$this->baseUrl}/materias", $data);
        
        if ($response->failed()) {
            throw ValidationException::withMessages($response->json()['errors'] ?? ['error' => 'Error al crear materia']);
        }

        return $response->json();
    }

    public function update($id, array $data)
    {
        $response = Http::put("{$this->baseUrl}/materias/{$id}", $data);
        
        if ($response->failed()) {
            throw ValidationException::withMessages($response->json()['errors'] ?? ['error' => 'Error al actualizar materia']);
        }

        return $response->json();
    }

    public function delete($id)
    {
        return Http::delete("{$this->baseUrl}/materias/{$id}")->json();
    }
}