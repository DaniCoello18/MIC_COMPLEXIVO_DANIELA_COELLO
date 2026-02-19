<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class MateriaService
{
    protected string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('services.node_api.url');
    }

    // GET /materias
    public function getAll()
    {
        return Http::get("{$this->baseUrl}/materias")->json();
    }

    // GET /materias/{id}
    public function getById($id)
    {
        return Http::get("{$this->baseUrl}/materias/{$id}")->json();
    }

    // POST /materias
    public function create(array $data)
    {
        $response = Http::post("{$this->baseUrl}/materias", $data);

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

    // PUT /materias/{id}
    public function update($id, array $data)
    {
        return Http::put("{$this->baseUrl}/materias/{$id}", $data)->json();
    }

    // DELETE /materias/{id}
    public function delete($id)
    {
        return Http::delete("{$this->baseUrl}/materias/{$id}")->json();
    }

    // GET /materias/search
    public function search(array $filters)
    {
        $filters = array_filter($filters);
        if (empty($filters)) {
            return $this->getAll();
        }
        return Http::get("{$this->baseUrl}/materias/search", $filters)->json();
    }
}
