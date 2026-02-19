<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;

class EspecialidadService
{
    protected string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('services.node_api.url');
    }

    public function getAll()
    {
        return Http::get("{$this->baseUrl}/especialidades")->json();
    }

    public function create(array $data)
    {
        $response = Http::post("{$this->baseUrl}/especialidades", $data);
        
        if ($response->failed()) {
            // Si el servicio devuelve errores (ej: código duplicado), Laravel los lanza al front
            throw ValidationException::withMessages($response->json()['errors'] ?? ['error' => 'Error al crear']);
        }

        return $response->json();
    }

    public function update($id, array $data)
    {
        $response = Http::put("{$this->baseUrl}/especialidades/{$id}", $data);
        
        if ($response->failed()) {
            throw ValidationException::withMessages($response->json()['errors'] ?? ['error' => 'Error al actualizar']);
        }

        return $response->json();
    }

    public function delete($id)
    {
        return Http::delete("{$this->baseUrl}/especialidades/{$id}")->json();
    }

    public function search(array $filters)
    {
        // Si hay búsqueda, usamos el endpoint /search?q=...
        if (!empty($filters['search'])) {
            return Http::get("{$this->baseUrl}/especialidades/search", [
                'q' => $filters['search']
            ])->json();
        }

        return $this->getAll();
    }
}