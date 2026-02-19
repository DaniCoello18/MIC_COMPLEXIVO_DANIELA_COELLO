<?php

namespace App\Http\Controllers;

use App\Services\HorarioService;
use App\Services\MateriaService;
use App\Services\EdificioService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class HorarioController extends Controller
{
    protected HorarioService $horarioService;
    protected MateriaService $materiaService;
    protected EdificioService $edificioService;

    public function __construct(
        HorarioService $horarioService,
        MateriaService $materiaService,
        EdificioService $edificioService
    ) {
        $this->horarioService = $horarioService;
        $this->materiaService = $materiaService;
        $this->edificioService = $edificioService;
    }

    public function index(Request $request)
    {
        $search = $request->input('search');
        
        return Inertia::render('Horarios/Index', [
            'horarios' => $this->horarioService->search(['search' => $search]) ?? [],
            'materias' => $this->materiaService->getAll() ?? [],
            'edificios' => $this->edificioService->getAll() ?? [],
            'filters' => ['search' => $search],
        ]);
    }

    public function store(Request $request)
    {
        $this->horarioService->create($request->all());
        return redirect()->route('horarios.index');
    }

    public function update(Request $request, $id)
    {
        $this->horarioService->update($id, $request->all());
        return redirect()->route('horarios.index');
    }

    public function destroy($id)
    {
        $this->horarioService->delete($id);
        return redirect()->route('horarios.index');
    }
}