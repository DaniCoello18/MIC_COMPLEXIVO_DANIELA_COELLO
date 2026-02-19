<?php

namespace App\Http\Controllers;

use App\Services\MateriaService;
use App\Services\EspecialidadService; // Inyectado
use Illuminate\Http\Request;
use Inertia\Inertia;

class MateriaController extends Controller
{
    protected MateriaService $materiaService;
    protected EspecialidadService $especialidadService;

    public function __construct(
        MateriaService $materiaService,
        EspecialidadService $especialidadService
    ) {
        $this->materiaService = $materiaService;
        $this->especialidadService = $especialidadService;
    }

    public function index(Request $request)
    {
        $search = $request->input('search');
        
        // Obtenemos materias (filtradas o no)
        $materias = $this->materiaService->search(['search' => $search]);
        
        // Obtenemos especialidades para el select del formulario
        $especialidades = $this->especialidadService->getAll();

        return Inertia::render('Materias/Index', [
            'materias' => $materias ?? [],
            'especialidades' => $especialidades ?? [],
            'filters' => ['search' => $search],
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'codigo' => 'required',
            'nombre' => 'required',
            'especialidad_id' => 'required'
        ]);

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