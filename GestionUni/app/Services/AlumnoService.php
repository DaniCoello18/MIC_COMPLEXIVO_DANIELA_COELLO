<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class AlumnoService
{
    protected string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('services.node_api.url');
    }

    // GET /alumnos
    public function getAll()
    {
        return Http::get("{$this->baseUrl}/alumnos")->json();
    }

    // GET /alumnos/{id}
    public function getById($id)
    {
        return Http::get("{$this->baseUrl}/alumnos/{$id}")->json();
    }

    // POST /alumnos
public function create(array $data)
{
    $response = Http::post("{$this->baseUrl}/alumnos", $data);

    if ($response->status() === 422) {

        $errors = [];

        foreach ($response->json()['errors'] ?? [] as $error) {
            $field = $error['path'] ?? null;
            $message = $error['msg'] ?? 'Error de validación';

            if ($field) {
                $errors[$field][] = $message;
            }
        }

        throw \Illuminate\Validation\ValidationException::withMessages($errors);
    }

    if ($response->failed()) {
        abort($response->status(), $response->json()['message'] ?? 'Error en la API');
    }

    return $response->json();
}

    // PUT /alumnos/{id}
    public function update($id, array $data)
    {
        return Http::put("{$this->baseUrl}/alumnos/{$id}", $data)->json();
    }

    // DELETE /alumnos/{id}
    public function delete($id)
    {
        return Http::delete("{$this->baseUrl}/alumnos/{$id}")->json();
    }

    // GET /alumnos/search?nombre=...
    public function search(array $filters)
    {
        // Eliminar valores vacíos
        $filters = array_filter($filters);

        // Si no hay filtros → traer todos
        if (empty($filters)) {
            return $this->getAll();
        }

        // Si hay filtros → llamar a /search
        return Http::get("{$this->baseUrl}/alumnos/search", $filters)->json();
    }
}
