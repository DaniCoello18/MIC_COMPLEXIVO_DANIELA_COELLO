<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class MatriculaService
{
    protected string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('services.node_api.url');
    }

    // GET /matriculas
    public function getAll()
    {
        return Http::get("{$this->baseUrl}/matriculas")->json();
    }

    // GET /matriculas/{id}
    public function getById($id)
    {
        return Http::get("{$this->baseUrl}/matriculas/{$id}")->json();
    }

    // POST /matriculas
    public function create(array $data)
    {
        $response = Http::post("{$this->baseUrl}/matriculas", $data);

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

    // PUT /matriculas/{id}
    public function update($id, array $data)
    {
        return Http::put("{$this->baseUrl}/matriculas/{$id}", $data)->json();
    }

    // DELETE /matriculas/{id}
    public function delete($id)
    {
        return Http::delete("{$this->baseUrl}/matriculas/{$id}")->json();
    }

    // GET /matriculas/search
    public function search(array $filters)
    {
        $filters = array_filter($filters);
        if (empty($filters)) {
            return $this->getAll();
        }
        return Http::get("{$this->baseUrl}/matriculas/search", $filters)->json();
    }
}
