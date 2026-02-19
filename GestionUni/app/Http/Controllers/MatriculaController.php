<?php

namespace App\Http\Controllers;

use App\Services\MatriculaService;
use App\Services\AlumnoService;
use App\Services\MateriaService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MatriculaController extends Controller
{
    protected MatriculaService $matriculaService;
    protected AlumnoService $alumnoService;
    protected MateriaService $materiaService;

    public function __construct(
        MatriculaService $matriculaService,
        AlumnoService $alumnoService,
        MateriaService $materiaService
    ) {
        $this->matriculaService = $matriculaService;
        $this->alumnoService = $alumnoService;
        $this->materiaService = $materiaService;
    }

    public function index(Request $request)
    {
        $search = $request->input('search');
        
        return Inertia::render('Matriculas/Index', [
            'matriculas' => $this->matriculaService->search(['search' => $search]) ?? [],
            'alumnos' => $this->alumnoService->getAll() ?? [],
            'materias' => $this->materiaService->getAll() ?? [],
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