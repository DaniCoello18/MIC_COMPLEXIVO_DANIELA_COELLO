<script setup lang="ts">
import { Head, usePage } from '@inertiajs/vue3'
import { computed } from 'vue'
import AppLayout from '@/layouts/AppLayout.vue'
import { dashboard } from '@/routes'
import { type BreadcrumbItem } from '@/types'

const page = usePage()

const user = computed(() => page.props.auth.user)

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Bienvenido',
        href: dashboard().url,
    },
]
</script>

<template>
    <Head title="Bienvenido" />

    <AppLayout :breadcrumbs="breadcrumbs">

        <div class="flex flex-1 flex-col gap-6 p-6">

            <!-- Card Bienvenida -->
            <div class="rounded-xl border border-sidebar-border/70 bg-white p-8 shadow-sm dark:border-sidebar-border dark:bg-neutral-900">
                
                <h1 class="text-3xl font-bold text-slate-800 dark:text-white">
                    Bienvenido, {{ user?.name }} 👋
                </h1>

                <p class="mt-3 text-slate-600 dark:text-slate-400">
                    Has ingresado correctamente al sistema de 
                    <span class="font-semibold text-indigo-600">
                        Gestión de Control Universitario
                    </span>.
                </p>

                <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">
                    Desde aquí podrás administrar estudiantes, docentes,
                    matrículas y procesos académicos de manera segura y eficiente.
                </p>

            </div>

            <!-- Cards informativas -->
            <div class="grid gap-6 md:grid-cols-3">

                <div class="rounded-xl border border-sidebar-border/70 bg-white p-6 shadow-sm dark:border-sidebar-border dark:bg-neutral-900">
                    <h3 class="text-lg font-semibold text-slate-800 dark:text-white">
                        Estudiantes
                    </h3>
                    <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">
                        Gestión de registros, matrículas y seguimiento académico.
                    </p>
                </div>

                <div class="rounded-xl border border-sidebar-border/70 bg-white p-6 shadow-sm dark:border-sidebar-border dark:bg-neutral-900">
                    <h3 class="text-lg font-semibold text-slate-800 dark:text-white">
                        Docentes
                    </h3>
                    <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">
                        Administración de profesores y asignaciones académicas.
                    </p>
                </div>

                <div class="rounded-xl border border-sidebar-border/70 bg-white p-6 shadow-sm dark:border-sidebar-border dark:bg-neutral-900">
                    <h3 class="text-lg font-semibold text-slate-800 dark:text-white">
                        Reportes
                    </h3>
                    <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">
                        Consulta de estadísticas y control institucional.
                    </p>
                </div>

            </div>

        </div>

    </AppLayout>
</template>
