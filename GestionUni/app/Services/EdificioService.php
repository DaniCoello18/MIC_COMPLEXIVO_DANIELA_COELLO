<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class EdificioService
{
    protected string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('services.node_api.url');
    }

    // GET /edificios
    public function getAll()
    {
        return Http::get("{$this->baseUrl}/edificios")->json();
    }

    // GET /edificios/{id}
    public function getById($id)
    {
        return Http::get("{$this->baseUrl}/edificios/{$id}")->json();
    }

    // POST /edificios
    public function create(array $data)
    {
        $response = Http::post("{$this->baseUrl}/edificios", $data);

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

    // PUT /edificios/{id}
    public function update($id, array $data)
    {
        return Http::put("{$this->baseUrl}/edificios/{$id}", $data)->json();
    }

    // DELETE /edificios/{id}
    public function delete($id)
    {
        return Http::delete("{$this->baseUrl}/edificios/{$id}")->json();
    }

    // GET /edificios/search
    public function search(array $filters)
    {
        $filters = array_filter($filters);
        if (empty($filters)) {
            return $this->getAll();
        }
        return Http::get("{$this->baseUrl}/edificios/search", $filters)->json();
    }
}
