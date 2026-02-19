<?php

namespace App\Http\Controllers;

use App\Services\ProfesorService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProfesorController extends Controller
{
    protected ProfesorService $profesorService;

    public function __construct(ProfesorService $profesorService)
    {
        $this->profesorService = $profesorService;
    }

    public function index(Request $request)
    {
        $search = $request->input('search');
        $filters = [];
        if ($search) {
            $filters['search'] = $search;
        }
        $profesores = $this->profesorService->search($filters);

        return Inertia::render('Profesores/Index', [
            'profesores' => $profesores ?? [],
            'filters' => ['search' => $search],
        ]);
    }

    public function store(Request $request)
    {
        $this->profesorService->create($request->all());
        return redirect()->route('profesores.index');
    }

    public function update(Request $request, $id)
    {
        $this->profesorService->update($id, $request->all());
        return redirect()->route('profesores.index');
    }

    public function destroy($id)
    {
        $this->profesorService->delete($id);
        return redirect()->route('profesores.index');
    }
}
