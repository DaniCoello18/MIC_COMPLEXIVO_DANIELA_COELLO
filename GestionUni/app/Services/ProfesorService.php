<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class ProfesorService
{
    protected string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('services.node_api.url');
    }

    // GET /profesores
    public function getAll()
    {
        return Http::get("{$this->baseUrl}/profesores")->json();
    }

    // GET /profesores/{id}
    public function getById($id)
    {
        return Http::get("{$this->baseUrl}/profesores/{$id}")->json();
    }

    // POST /profesores
    public function create(array $data)
    {
        $response = Http::post("{$this->baseUrl}/profesores", $data);

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

    // PUT /profesores/{id}
    public function update($id, array $data)
    {
        return Http::put("{$this->baseUrl}/profesores/{$id}", $data)->json();
    }

    // DELETE /profesores/{id}
    public function delete($id)
    {
        return Http::delete("{$this->baseUrl}/profesores/{$id}")->json();
    }

    // GET /profesores/search
    public function search(array $filters)
    {
        $filters = array_filter($filters);
        if (empty($filters)) {
            return $this->getAll();
        }
        return Http::get("{$this->baseUrl}/profesores/search", $filters)->json();
    }
}
