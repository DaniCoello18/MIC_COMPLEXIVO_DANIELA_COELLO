<?php

namespace App\Http\Controllers;

use App\Services\AlumnoService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AlumnoController extends Controller
{
    protected AlumnoService $alumnoService;

    public function __construct(AlumnoService $alumnoService)
    {
        $this->alumnoService = $alumnoService;
    }

    public function index(Request $request)
{
    $search = $request->input('search');

    $filters = [];

    if ($search) {
        $filters = [
            'cedula' => $search,
            'nombre' => $search,
            'apellido' => $search,
            'correo' => $search,
        ];
    }

    $alumnos = $this->alumnoService->search($filters);

    return Inertia::render('Alumnos/Index', [
        'alumnos' => $alumnos ?? [],
        'filters' => [
            'search' => $search
        ]
    ]);
}


    public function store(Request $request)
    {
        $this->alumnoService->create($request->all());

        return redirect()->route('alumnos.index');
    }

    public function update(Request $request, $id)
    {
        $this->alumnoService->update($id, $request->all());

        return redirect()->route('alumnos.index');
    }

    public function destroy($id)
    {
        $this->alumnoService->delete($id);

        return redirect()->route('alumnos.index');
    }
}
