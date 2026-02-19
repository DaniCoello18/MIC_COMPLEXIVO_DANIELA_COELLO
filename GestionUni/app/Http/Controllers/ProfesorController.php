<?php

namespace App\Http\Controllers;

use App\Services\ProfesorService;
use App\Services\EspecialidadService; // Inyectamos el de especialidades
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProfesorController extends Controller
{
    protected ProfesorService $profesorService;
    protected EspecialidadService $especialidadService;

    public function __construct(
        ProfesorService $profesorService, 
        EspecialidadService $especialidadService
    ) {
        $this->profesorService = $profesorService;
        $this->especialidadService = $especialidadService;
    }

    public function index(Request $request)
    {
        $search = $request->input('search');
        
        // Usamos los services para traer la data
        $profesores = $this->profesorService->search(['search' => $search]);
        $especialidades = $this->especialidadService->getAll();

        return Inertia::render('Profesores/Index', [
            'profesores' => $profesores ?? [],
            'especialidades' => $especialidades ?? [],
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