<?php

namespace App\Http\Controllers;

use App\Services\MateriaService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MateriaController extends Controller
{
    protected MateriaService $materiaService;

    public function __construct(MateriaService $materiaService)
    {
        $this->materiaService = $materiaService;
    }

    public function index(Request $request)
    {
        $search = $request->input('search');
        $filters = [];
        if ($search) {
            $filters['search'] = $search;
        }
        $materias = $this->materiaService->search($filters);

        return Inertia::render('Materias/Index', [
            'materias' => $materias ?? [],
            'filters' => ['search' => $search],
        ]);
    }

    public function store(Request $request)
    {
        $this->materiaService->create($request->all());
        return redirect()->route('materias.index');
    }

    public function update(Request $request, $id)
    {
        $this->materiaService->update($id, $request->all());
        return redirect()->route('materias.index');
    }

    public function destroy($id)
    {
        $this->materiaService->delete($id);
        return redirect()->route('materias.index');
    }
}
