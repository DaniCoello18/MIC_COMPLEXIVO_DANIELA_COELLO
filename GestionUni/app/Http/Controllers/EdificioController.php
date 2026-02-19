<?php

namespace App\Http\Controllers;

use App\Services\EdificioService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class EdificioController extends Controller
{
    protected EdificioService $edificioService;

    public function __construct(EdificioService $edificioService)
    {
        $this->edificioService = $edificioService;
    }

    public function index(Request $request)
    {
        $search = $request->input('search');
        $filters = [];
        if ($search) {
            $filters['search'] = $search;
        }
        $edificios = $this->edificioService->search($filters);

        return Inertia::render('Edificios/Index', [
            'edificios' => $edificios ?? [],
            'filters' => ['search' => $search],
        ]);
    }

    public function store(Request $request)
    {
        $this->edificioService->create($request->all());
        return redirect()->route('edificios.index');
    }

    public function update(Request $request, $id)
    {
        $this->edificioService->update($id, $request->all());
        return redirect()->route('edificios.index');
    }

    public function destroy($id)
    {
        $this->edificioService->delete($id);
        return redirect()->route('edificios.index');
    }
}
