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

// Página principal
Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');

// Dashboard
Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// --- RUTAS PROTEGIDAS ---
Route::middleware(['auth'])->group(function () {

    // --- SOLO LECTURA PARA TODOS ---
    $readOnly = ['index','show'];

    Route::resource('alumnos', AlumnoController::class)->only($readOnly);
    Route::get('alumnos/buscar', [AlumnoController::class,'search'])->name('alumnos.buscar');

    Route::resource('especialidades', EspecialidadController::class)->only($readOnly);
    Route::get('especialidades/buscar', [EspecialidadController::class,'search'])->name('especialidades.buscar');

    Route::resource('profesores', ProfesorController::class)->only($readOnly);
    Route::get('profesores/buscar', [ProfesorController::class,'search'])->name('profesores.buscar');

    Route::resource('edificios', EdificioController::class)->only($readOnly);
    Route::get('edificios/buscar', [EdificioController::class,'search'])->name('edificios.buscar');

    Route::resource('materias', MateriaController::class)->only($readOnly);
    Route::get('materias/buscar', [MateriaController::class,'search'])->name('materias.buscar');

    Route::resource('horarios', HorarioController::class)->only($readOnly);
    Route::get('horarios/buscar', [HorarioController::class,'search'])->name('horarios.buscar');

    Route::resource('matriculas', MatriculaController::class)->only($readOnly);
    Route::get('matriculas/buscar', [MatriculaController::class,'search'])->name('matriculas.buscar');

    Route::resource('inventarios', InventarioController::class)->only($readOnly);
    Route::get('inventarios/buscar', [InventarioController::class,'search'])->name('inventarios.buscar');

    Route::resource('inventarios1', Inventario1Controller::class)->only($readOnly);
    Route::get('inventarios1/buscar', [Inventario1Controller::class,'search'])->name('inventarios1.buscar');

    // --- SOLO ADMINS: CREAR, ACTUALIZAR, ELIMINAR ---
    $writeOnly = ['store','update','destroy'];

    Route::middleware(['role:admin'])->group(function () use ($writeOnly) {
        Route::resource('alumnos', AlumnoController::class)->only($writeOnly);
        Route::resource('especialidades', EspecialidadController::class)->only($writeOnly);
        Route::resource('profesores', ProfesorController::class)->only($writeOnly);
        Route::resource('edificios', EdificioController::class)->only($writeOnly);
        Route::resource('materias', MateriaController::class)->only($writeOnly);
        Route::resource('horarios', HorarioController::class)->only($writeOnly);
        Route::resource('matriculas', MatriculaController::class)->only($writeOnly);
        Route::resource('inventarios', InventarioController::class)->only($writeOnly);
        Route::resource('inventarios1', Inventario1Controller::class)->only($writeOnly);
    });

});

require __DIR__.'/settings.php';
