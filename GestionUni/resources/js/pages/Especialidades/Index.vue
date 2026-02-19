<script setup lang="ts">
import { Head, useForm, router } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import { ref, watch } from 'vue'
import { type BreadcrumbItem } from '@/types'

interface Especialidad {
    id: number
    codigo: string
    nombre: string
}

const props = defineProps<{
    especialidades: Especialidad[]
    filters?: {
        search?: string
    }
}>()

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Gestión de Especialidades', href: '/especialidades' }
]

// --- BÚSQUEDA REACTIVA ---
const search = ref(props.filters?.search ?? '')

watch(search, (value) => {
    router.get('/especialidades', { search: value }, {
        preserveState: true,
        replace: true,
        preserveScroll: true
    })
})

// --- CRUD: CREAR ---
const form = useForm({
    codigo: '',
    nombre: ''
})

const submit = () => {
    form.post('/especialidades', {
        onSuccess: () => form.reset()
    })
}

// --- CRUD: EDITAR ---
const editForm = useForm({
    id: 0,
    codigo: '',
    nombre: ''
})

const showEditModal = ref(false)

const startEdit = (especialidad: Especialidad) => {
    editForm.clearErrors()
    editForm.id = especialidad.id
    editForm.codigo = especialidad.codigo
    editForm.nombre = especialidad.nombre
    showEditModal.value = true
}

const update = () => {
    editForm.put(`/especialidades/${editForm.id}`, {
        onSuccess: () => {
            showEditModal.value = false
            editForm.reset()
        }
    })
}

// --- CRUD: ELIMINAR ---
const eliminar = (id: number) => {
    if (confirm('¿Estás seguro de eliminar esta especialidad?')) {
        router.delete(`/especialidades/${id}`)
    }
}
</script>

<template>
    <Head title="Especialidades" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6 space-y-6 max-w-7xl mx-auto">
            
            <div class="bg-white p-4 rounded-lg shadow border border-gray-200">
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </span>
                    <input
                        v-model="search"
                        type="text"
                        placeholder="Buscar por código o nombre de especialidad..."
                        class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition-all"
                    />
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="md:col-span-1 bg-white p-6 rounded-lg shadow border border-gray-200 h-fit">
                    <h2 class="text-xl font-bold mb-4 text-gray-700">Nueva Especialidad</h2>
                    <form @submit.prevent="submit" class="space-y-4">
                        <div>
                            <input v-model="form.codigo" placeholder="Código (ej: SIST-01)" :class="{'border-red-500 ring-1 ring-red-200': form.errors.codigo}" class="w-full border p-2 rounded focus:ring-2 focus:ring-blue-500 outline-none" />
                            <p v-if="form.errors.codigo" class="text-red-500 text-xs mt-1 font-semibold">{{ form.errors.codigo }}</p>
                        </div>

                        <div>
                            <input v-model="form.nombre" placeholder="Nombre de Especialidad" :class="{'border-red-500': form.errors.nombre}" class="w-full border p-2 rounded outline-none" />
                            <p v-if="form.errors.nombre" class="text-red-500 text-xs mt-1">{{ form.errors.nombre }}</p>
                        </div>

                        <button type="submit" :disabled="form.processing" class="w-full bg-blue-600 text-white p-2 rounded font-semibold hover:bg-blue-700 transition disabled:opacity-50 shadow-md shadow-blue-100">
                            {{ form.processing ? 'Procesando...' : 'Registrar Especialidad' }}
                        </button>
                    </form>
                </div>

                <div class="md:col-span-2 bg-white rounded-lg shadow border border-gray-200 overflow-hidden">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50 border-b">
                            <tr>
                                <th class="px-4 py-3 text-sm font-bold text-gray-600">Código</th>
                                <th class="px-4 py-3 text-sm font-bold text-gray-600">Especialidad</th>
                                <th class="px-4 py-3 text-sm font-bold text-gray-600 text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <tr v-for="esp in especialidades" :key="esp.id" class="hover:bg-gray-50 transition">
                                <td class="px-4 py-3 text-sm font-mono text-blue-600 font-bold">{{ esp.codigo }}</td>
                                <td class="px-4 py-3 text-sm text-gray-700">{{ esp.nombre }}</td>
                                <td class="px-4 py-3 text-right space-x-3">
                                    <button @click="startEdit(esp)" class="text-blue-600 hover:text-blue-800 font-bold text-xs uppercase">Editar</button>
                                    <button @click="eliminar(esp.id)" class="text-red-600 hover:text-red-800 font-bold text-xs uppercase">Eliminar</button>
                                </td>
                            </tr>
                            <tr v-if="especialidades.length === 0">
                                <td colspan="3" class="px-4 py-10 text-center text-gray-400 italic">
                                    No se encontraron especialidades registradas.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div v-if="showEditModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 p-4">
                <div class="bg-white rounded-xl shadow-2xl w-full max-w-md overflow-hidden">
                    <div class="p-6 space-y-4">
                        <h2 class="text-xl font-bold text-gray-800 border-b pb-2">Actualizar Especialidad</h2>
                        
                        <div class="space-y-3">
                            <div>
                                <label class="text-[10px] font-bold text-gray-400 uppercase">Código</label>
                                <input v-model="editForm.codigo" :class="{'border-red-500': editForm.errors.codigo}" class="w-full border p-2 rounded text-sm mt-1 focus:ring-2 focus:ring-blue-500 outline-none" />
                                <p v-if="editForm.errors.codigo" class="text-red-500 text-xs mt-1">{{ editForm.errors.codigo }}</p>
                            </div>
                            
                            <div>
                                <label class="text-[10px] font-bold text-gray-400 uppercase">Nombre</label>
                                <input v-model="editForm.nombre" :class="{'border-red-500': editForm.errors.nombre}" class="w-full border p-2 rounded text-sm mt-1 focus:ring-2 focus:ring-blue-500 outline-none" />
                                <p v-if="editForm.errors.nombre" class="text-red-500 text-xs mt-1">{{ editForm.errors.nombre }}</p>
                            </div>
                        </div>

                        <div class="flex justify-end gap-3 mt-6">
                            <button @click="showEditModal = false" class="px-4 py-2 text-sm font-bold text-gray-500 hover:bg-gray-50 rounded transition">CANCELAR</button>
                            <button @click="update" :disabled="editForm.processing" class="px-6 py-2 bg-blue-600 text-white rounded-lg font-bold text-sm hover:bg-blue-700 shadow-lg shadow-blue-200 transition">
                                {{ editForm.processing ? 'GUARDANDO...' : 'ACTUALIZAR' }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </AppLayout>
</template>