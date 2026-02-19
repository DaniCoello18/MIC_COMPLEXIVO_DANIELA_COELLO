<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import { ref } from 'vue'
import { type BreadcrumbItem } from '@/types'

interface Inventario {
    id: number
    nombre: string
}

const props = defineProps<{
    inventarios: Inventario[]
    filters?: { search?: string }
}>()

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Inventarios', href: '/inventarios' }
]

const form = useForm({ nombre: '' })
const submit = () => {
    form.post('/inventarios', {
        onSuccess: () => form.reset()
    })
}

const editForm = useForm({ id: 0, nombre: '' })
const showEditModal = ref(false)
const startEdit = (inv: Inventario) => {
    editForm.clearErrors()
    Object.assign(editForm, inv)
    showEditModal.value = true
}
const update = () => {
    editForm.put(`/inventarios/${editForm.id}`, {
        onSuccess: () => {
            showEditModal.value = false
            editForm.reset()
        }
    })
}
const eliminar = (id: number) => {
    if (confirm('¿Eliminar inventario?')) {
        editForm.delete(`/inventarios/${id}`)
    }
}

const searchForm = useForm({ search: props.filters?.search ?? '' })
const buscar = () => {
    searchForm.get('/inventarios', { preserveState: true, replace: true })
}
const limpiarBusqueda = () => {
    searchForm.reset()
    searchForm.get('/inventarios', { replace: true })
}
</script>

<template>
    <Head title="Inventarios" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6 space-y-8">
            <div class="bg-white p-4 rounded shadow">
                <h2 class="text-lg font-bold mb-4">Nuevo Inventario</h2>
                <form @submit.prevent="submit" class="grid grid-cols-2 gap-4">
                    <div>
                        <input v-model="form.nombre" placeholder="Nombre" class="border p-2 rounded w-full" />
                        <div v-if="form.errors.nombre" class="text-red-500 text-sm">
                            {{ form.errors.nombre }}
                        </div>
                    </div>
                    <button type="submit" class="col-span-2 bg-blue-500 text-white p-2 rounded hover:bg-blue-600" :disabled="form.processing">
                        Guardar
                    </button>
                </form>
            </div>

            <div class="bg-white p-4 rounded shadow">
                <h2 class="text-lg font-bold mb-4">Buscar</h2>
                <div class="flex gap-4">
                    <input v-model="searchForm.search" @input="buscar" placeholder="Buscar..." class="border p-2 rounded w-full" />
                    <button @click="limpiarBusqueda" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                        Limpiar
                    </button>
                </div>
            </div>

            <div class="bg-white p-4 rounded shadow">
                <h2 class="text-lg font-bold mb-4">Lista de Inventarios</h2>
                <table class="w-full border">
                    <thead>
                        <tr class="bg-gray-200">
                            <th class="p-2 border">ID</th>
                            <th class="p-2 border">Nombre</th>
                            <th class="p-2 border">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="inv in inventarios" :key="inv.id">
                            <td class="border p-2">{{ inv.id }}</td>
                            <td class="border p-2">{{ inv.nombre }}</td>
                            <td class="border p-2 space-x-2">
                                <button @click="startEdit(inv)" class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600">Editar</button>
                                <button @click="eliminar(inv.id)" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">Eliminar</button>
                            </td>
                        </tr>
                        <tr v-if="inventarios.length === 0">
                            <td colspan="3" class="text-center p-4">No hay registros</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AppLayout>
</template>