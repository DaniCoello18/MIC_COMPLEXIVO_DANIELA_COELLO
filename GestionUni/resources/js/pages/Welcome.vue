<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3'
import AppLogoIcon from '@/components/AppLogoIcon.vue'
</script>

<template>
    <Head title="Gestión de Control Universitario" />

    <div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-200 flex flex-col">

        <!-- HEADER -->
        <header class="w-full bg-white shadow-sm">
            <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">

                <!-- Logo + Nombre -->
                <div class="flex items-center gap-3">
                    <AppLogoIcon class="h-10 w-auto text-indigo-600" />
                    <span class="text-xl font-semibold text-slate-800">
                        Gestión de Control Universitario
                    </span>
                </div>

                <!-- Botones -->
                <div class="flex gap-4">
                    <Link
                        href="/login"
                        class="px-4 py-2 text-sm font-medium text-slate-700 hover:text-indigo-600 transition"
                    >
                        Iniciar Sesión
                    </Link>

                    <Link
                        href="/register"
                        class="px-4 py-2 text-sm font-medium bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition"
                    >
                        Registrarse
                    </Link>
                </div>

            </div>
        </header>

        <!-- HERO -->
        <main class="flex-1 flex items-center">
            <div class="max-w-7xl mx-auto grid md:grid-cols-2 gap-12 items-center px-6 py-16">

                <!-- Texto -->
                <div class="space-y-6">
                    <h1 class="text-4xl md:text-5xl font-bold text-slate-800 leading-tight">
                        Plataforma Integral para la
                        <span class="text-indigo-600">
                            Gestión Académica Universitaria
                        </span>
                    </h1>

                    <p class="text-slate-600 text-lg">
                        Sistema diseñado para optimizar el control académico,
                        la administración de estudiantes, docentes y procesos
                        institucionales. Mejora la eficiencia institucional,
                        fortalece la trazabilidad y garantiza seguridad en la
                        gestión universitaria.
                    </p>

                    <div class="flex gap-4">
                        <Link
                            href="/login"
                            class="px-6 py-3 bg-indigo-600 text-white rounded-lg shadow hover:bg-indigo-700 transition"
                        >
                            Acceder al Sistema
                        </Link>

                        <Link
                            href="/register"
                            class="px-6 py-3 border border-indigo-600 text-indigo-600 rounded-lg hover:bg-indigo-50 transition"
                        >
                            Crear Cuenta
                        </Link>
                    </div>
                </div>

                <!-- Imagen -->
                <div class="flex justify-center">
                    <img
                        src="/images/universidadwelcome.jpg"
                        alt="Gestión Universitaria"
                        class="rounded-xl shadow-xl max-h-[450px] object-cover"
                    />
                </div>

            </div>
        </main>

        <!-- FOOTER -->
        <footer class="text-center p-6 text-sm text-slate-500 bg-white">
            © 2026 Gestión de Control Universitario · Todos los derechos reservados
        </footer>

    </div>
</template>
