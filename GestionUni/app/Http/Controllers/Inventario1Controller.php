<?php

namespace App\Http\Controllers;

use App\Services\Inventario1Service;
use Illuminate\Http\Request;
use Inertia\Inertia;

class Inventario1Controller extends Controller
{
    protected Inventario1Service $inventario1Service;

    public function __construct(Inventario1Service $inventario1Service)
    {
        $this->inventario1Service = $inventario1Service;
    }

    public function index(Request $request)
    {
        $search = $request->input('search');
        $filters = [];
        if ($search) {
            $filters['search'] = $search;
        }
        $inventarios1 = $this->inventario1Service->search($filters);

        return Inertia::render('Inventarios1/Index', [
            'inventarios1' => $inventarios1 ?? [],
            'filters' => ['search' => $search],
        ]);
    }

    public function store(Request $request)
    {
        $this->inventario1Service->create($request->all());
        return redirect()->route('inventarios1.index');
    }

    public function update(Request $request, $id)
    {
        $this->inventario1Service->update($id, $request->all());
        return redirect()->route('inventarios1.index');
    }

    public function destroy($id)
    {
        $this->inventario1Service->delete($id);
        return redirect()->route('inventarios1.index');
    }
}
