<?php

namespace App\Http\Controllers;

use App\Services\HorarioService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class HorarioController extends Controller
{
    protected HorarioService $horarioService;

    public function __construct(HorarioService $horarioService)
    {
        $this->horarioService = $horarioService;
    }

    public function index(Request $request)
    {
        $search = $request->input('search');
        $filters = [];
        if ($search) {
            $filters['search'] = $search;
        }
        $horarios = $this->horarioService->search($filters);

        return Inertia::render('Horarios/Index', [
            'horarios' => $horarios ?? [],
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
