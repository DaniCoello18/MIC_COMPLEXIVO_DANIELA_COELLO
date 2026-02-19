<?php

namespace App\Http\Controllers;

use App\Services\MatriculaService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MatriculaController extends Controller
{
    protected MatriculaService $matriculaService;

    public function __construct(MatriculaService $matriculaService)
    {
        $this->matriculaService = $matriculaService;
    }

    public function index(Request $request)
    {
        $search = $request->input('search');
        $filters = [];
        if ($search) {
            $filters['search'] = $search;
        }
        $matriculas = $this->matriculaService->search($filters);

        return Inertia::render('Matriculas/Index', [
            'matriculas' => $matriculas ?? [],
            'filters' => ['search' => $search],
        ]);
    }

    public function store(Request $request)
    {
        $this->matriculaService->create($request->all());
        return redirect()->route('matriculas.index');
    }

    public function update(Request $request, $id)
    {
        $this->matriculaService->update($id, $request->all());
        return redirect()->route('matriculas.index');
    }

    public function destroy($id)
    {
        $this->matriculaService->delete($id);
        return redirect()->route('matriculas.index');
    }
}
