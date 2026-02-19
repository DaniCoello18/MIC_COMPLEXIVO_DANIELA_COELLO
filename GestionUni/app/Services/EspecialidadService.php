<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class EspecialidadService
{
    protected string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('services.node_api.url');
    }

    // GET /especialidades
    public function getAll()
    {
        return Http::get("{$this->baseUrl}/especialidades")->json();
    }

    // GET /especialidades/{id}
    public function getById($id)
    {
        return Http::get("{$this->baseUrl}/especialidades/{$id}")->json();
    }

    // POST /especialidades
    public function create(array $data)
    {
        $response = Http::post("{$this->baseUrl}/especialidades", $data);

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

    // PUT /especialidades/{id}
    public function update($id, array $data)
    {
        return Http::put("{$this->baseUrl}/especialidades/{$id}", $data)->json();
    }

    // DELETE /especialidades/{id}
    public function delete($id)
    {
        return Http::delete("{$this->baseUrl}/especialidades/{$id}")->json();
    }

    // GET /especialidades/search?...
    public function search(array $filters)
    {
        $filters = array_filter($filters);
        if (empty($filters)) {
            return $this->getAll();
        }
        return Http::get("{$this->baseUrl}/especialidades/search", $filters)->json();
    }
}
