<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class HorarioService
{
    protected string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('services.node_api.url');
    }

    // GET /horarios
    public function getAll()
    {
        return Http::get("{$this->baseUrl}/horarios")->json();
    }

    // GET /horarios/{id}
    public function getById($id)
    {
        return Http::get("{$this->baseUrl}/horarios/{$id}")->json();
    }

    // POST /horarios
    public function create(array $data)
    {
        $response = Http::post("{$this->baseUrl}/horarios", $data);

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

    // PUT /horarios/{id}
    public function update($id, array $data)
    {
        return Http::put("{$this->baseUrl}/horarios/{$id}", $data)->json();
    }

    // DELETE /horarios/{id}
    public function delete($id)
    {
        return Http::delete("{$this->baseUrl}/horarios/{$id}")->json();
    }

    // GET /horarios/search
    public function search(array $filters)
    {
        $filters = array_filter($filters);
        if (empty($filters)) {
            return $this->getAll();
        }
        return Http::get("{$this->baseUrl}/horarios/search", $filters)->json();
    }
}
