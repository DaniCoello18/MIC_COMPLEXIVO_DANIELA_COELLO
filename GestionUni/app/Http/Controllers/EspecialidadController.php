<?php

namespace App\Http\Controllers;

use App\Services\EspecialidadService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class EspecialidadController extends Controller
{
    protected EspecialidadService $especialidadService;

    public function __construct(EspecialidadService $especialidadService)
    {
        $this->especialidadService = $especialidadService;
    }

    public function index(Request $request)
    {
        $search = $request->input('search');
        $filters = [];
        if ($search) {
            $filters['search'] = $search;
        }
        $especialidades = $this->especialidadService->search($filters);

        return Inertia::render('Especialidades/Index', [
            'especialidades' => $especialidades ?? [],
            'filters' => ['search' => $search],
        ]);
    }

    public function store(Request $request)
    {
        $this->especialidadService->create($request->all());
        return redirect()->route('especialidades.index');
    }

    public function update(Request $request, $id)
    {
        $this->especialidadService->update($id, $request->all());
        return redirect()->route('especialidades.index');
    }

    public function destroy($id)
    {
        $this->especialidadService->delete($id);
        return redirect()->route('especialidades.index');
    }
}
