<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class Inventario1Service
{
    protected string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('services.node_api.url');
    }

    // GET /inventarios1
    public function getAll()
    {
        return Http::get("{$this->baseUrl}/inventarios1")->json();
    }

    // GET /inventarios1/{id}
    public function getById($id)
    {
        return Http::get("{$this->baseUrl}/inventarios1/{$id}")->json();
    }

    // POST /inventarios1
    public function create(array $data)
    {
        $response = Http::post("{$this->baseUrl}/inventarios1", $data);

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

    // PUT /inventarios1/{id}
    public function update($id, array $data)
    {
        return Http::put("{$this->baseUrl}/inventarios1/{$id}", $data)->json();
    }

    // DELETE /inventarios1/{id}
    public function delete($id)
    {
        return Http::delete("{$this->baseUrl}/inventarios1/{$id}")->json();
    }

    // GET /inventarios1/search
    public function search(array $filters)
    {
        $filters = array_filter($filters);
        if (empty($filters)) {
            return $this->getAll();
        }
        return Http::get("{$this->baseUrl}/inventarios1/search", $filters)->json();
    }
}
