<?php

namespace App\Http\Controllers;

use App\Services\InventarioService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class InventarioController extends Controller
{
    protected InventarioService $inventarioService;

    public function __construct(InventarioService $inventarioService)
    {
        $this->inventarioService = $inventarioService;
    }

    public function index(Request $request)
    {
        $search = $request->input('search');
        $filters = [];
        if ($search) {
            $filters['search'] = $search;
        }
        $inventarios = $this->inventarioService->search($filters);

        return Inertia::render('Inventarios/Index', [
            'inventarios' => $inventarios ?? [],
            'filters' => ['search' => $search],
        ]);
    }

    public function store(Request $request)
    {
        $this->inventarioService->create($request->all());
        return redirect()->route('inventarios.index');
    }

    public function update(Request $request, $id)
    {
        $this->inventarioService->update($id, $request->all());
        return redirect()->route('inventarios.index');
    }

    public function destroy($id)
    {
        $this->inventarioService->delete($id);
        return redirect()->route('inventarios.index');
    }
}
