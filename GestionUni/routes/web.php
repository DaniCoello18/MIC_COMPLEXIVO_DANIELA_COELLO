<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;
use App\Http\Controllers\AlumnoController;
use App\Http\Controllers\EspecialidadController;
use App\Http\Controllers\ProfesorController;
use App\Http\Controllers\EdificioController;
use App\Http\Controllers\MateriaController;
use App\Http\Controllers\HorarioController;
use App\Http\Controllers\MatriculaController;
use App\Http\Controllers\InventarioController;
use App\Http\Controllers\Inventario1Controller;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth'])->group(function () {

    // CRUD estándar
    Route::resource('alumnos', AlumnoController::class)
        ->only(['index', 'store', 'update', 'destroy','show']);

    // Ruta separada para búsqueda
    Route::get('alumnos/buscar', [AlumnoController::class, 'search'])
        ->name('alumnos.buscar');

    // Especialidades
    Route::resource('especialidades', EspecialidadController::class)
        ->only(['index', 'store', 'update', 'destroy','show']);
    Route::get('especialidades/buscar', [EspecialidadController::class, 'search'])
        ->name('especialidades.buscar');

    // Profesores
    Route::resource('profesores', ProfesorController::class)
        ->only(['index', 'store', 'update', 'destroy','show']);
    Route::get('profesores/buscar', [ProfesorController::class, 'search'])
        ->name('profesores.buscar');

    // Edificios
    Route::resource('edificios', EdificioController::class)
        ->only(['index', 'store', 'update', 'destroy','show']);
    Route::get('edificios/buscar', [EdificioController::class, 'search'])
        ->name('edificios.buscar');

    // Materias
    Route::resource('materias', MateriaController::class)
        ->only(['index', 'store', 'update', 'destroy','show']);
    Route::get('materias/buscar', [MateriaController::class, 'search'])
        ->name('materias.buscar');

    // Horarios
    Route::resource('horarios', HorarioController::class)
        ->only(['index', 'store', 'update', 'destroy','show']);
    Route::get('horarios/buscar', [HorarioController::class, 'search'])
        ->name('horarios.buscar');

    // Matriculas
    Route::resource('matriculas', MatriculaController::class)
        ->only(['index', 'store', 'update', 'destroy','show']);
    Route::get('matriculas/buscar', [MatriculaController::class, 'search'])
        ->name('matriculas.buscar');

    // Inventarios
    Route::resource('inventarios', InventarioController::class)
        ->only(['index', 'store', 'update', 'destroy','show']);
    Route::get('inventarios/buscar', [InventarioController::class, 'search'])
        ->name('inventarios.buscar');

    // Inventarios1
    Route::resource('inventarios1', Inventario1Controller::class)
        ->only(['index', 'store', 'update', 'destroy','show']);
    Route::get('inventarios1/buscar', [Inventario1Controller::class, 'search'])
        ->name('inventarios1.buscar');
});

require __DIR__.'/settings.php';
