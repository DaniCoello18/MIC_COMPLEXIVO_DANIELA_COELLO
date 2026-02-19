<script setup lang="ts">
import { Head, useForm, router } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import { ref, watch } from 'vue'
import { type BreadcrumbItem } from '@/types'

interface Especialidad {
    id: number
    nombre: string
}

interface Materia {
    id: number
    codigo: string
    nombre: string
    descripcion: string
    especialidad_id: number
}

const props = defineProps<{
    materias: Materia[]
    especialidades: Especialidad[]
    filters?: { search?: string }
}>()

const breadcrumbs: BreadcrumbItem[] = [{ title: 'Gestión de Materias', href: '/materias' }]

// --- BÚSQUEDA REACTIVA ---
const search = ref(props.filters?.search ?? '')
watch(search, (value) => {
    router.get('/materias', { search: value }, { preserveState: true, replace: true, preserveScroll: true })
})

// --- CRUD: CREAR ---
const form = useForm({
    codigo: '',
    nombre: '',
    descripcion: '',
    especialidad_id: '' as string | number
})

const submit = () => {
    form.post('/materias', { onSuccess: () => form.reset() })
}

// --- CRUD: EDITAR ---
const editForm = useForm({
    id: 0,
    codigo: '',
    nombre: '',
    descripcion: '',
    especialidad_id: 0 as string | number
})

const showEditModal = ref(false)

const startEdit = (materia: Materia) => {
    editForm.clearErrors()
    editForm.id = materia.id
    editForm.codigo = materia.codigo
    editForm.nombre = materia.nombre
    editForm.descripcion = materia.descripcion || ''
    editForm.especialidad_id = materia.especialidad_id
    showEditModal.value = true
}

const update = () => {
    editForm.put(`/materias/${editForm.id}`, {
        onSuccess: () => {
            showEditModal.value = false
            editForm.reset()
        }
    })
}

// --- CRUD: ELIMINAR ---
const eliminar = (id: number) => {
    if (confirm('¿Estás seguro de eliminar esta materia?')) router.delete(`/materias/${id}`)
}

const getEspecialidadNombre = (id: number) => {
    return props.especialidades.find(e => e.id === id)?.nombre ?? 'Sin especialidad'
}
</script>

<template>
    <Head title="Materias" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6 space-y-6 max-w-7xl mx-auto">
            
            <div class="bg-white p-4 rounded-lg shadow border border-gray-200">
                <input v-model="search" type="text" placeholder="Filtrar por código, nombre o descripción..." class="w-full pl-4 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none" />
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="md:col-span-1 bg-white p-6 rounded-lg shadow border border-gray-200 h-fit">
                    <h2 class="text-xl font-bold mb-4 text-gray-700">Nueva Materia</h2>
                    <form @submit.prevent="submit" class="space-y-4">
                        <input v-model="form.codigo" placeholder="Código de Materia" class="w-full border p-2 rounded focus:ring-2 focus:ring-blue-500 outline-none" />
                        <input v-model="form.nombre" placeholder="Nombre de la Materia" class="w-full border p-2 rounded focus:ring-2 focus:ring-blue-500 outline-none" />
                        <textarea v-model="form.descripcion" placeholder="Descripción opcional" class="w-full border p-2 rounded focus:ring-2 focus:ring-blue-500 outline-none"></textarea>
                        
                        <select v-model="form.especialidad_id" class="w-full border p-2 rounded bg-white outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Seleccione Especialidad</option>
                            <option v-for="esp in especialidades" :key="esp.id" :value="esp.id">{{ esp.nombre }}</option>
                        </select>

                        <button type="submit" :disabled="form.processing" class="w-full bg-blue-600 text-white p-2 rounded font-semibold hover:bg-blue-700 transition shadow-md">
                            Registrar Materia
                        </button>
                    </form>
                </div>

                <div class="md:col-span-2 bg-white rounded-lg shadow border border-gray-200 overflow-hidden">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50 border-b">
                            <tr>
                                <th class="px-4 py-3 text-sm font-bold text-gray-600">Materia</th>
                                <th class="px-4 py-3 text-sm font-bold text-gray-600">Especialidad</th>
                                <th class="px-4 py-3 text-sm font-bold text-gray-600 text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <tr v-for="mat in materias" :key="mat.id" class="hover:bg-gray-50 transition">
                                <td class="px-4 py-3">
                                    <div class="font-bold text-gray-900 text-sm">[{{ mat.codigo }}] {{ mat.nombre }}</div>
                                    <div class="text-xs text-gray-500 truncate max-w-xs">{{ mat.descripcion }}</div>
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    <span class="px-2 py-1 bg-green-50 text-green-700 rounded text-xs font-bold uppercase border border-green-100">
                                        {{ getEspecialidadNombre(mat.especialidad_id) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-right space-x-3">
                                    <button @click="startEdit(mat)" class="text-blue-600 font-bold text-xs uppercase">Editar</button>
                                    <button @click="eliminar(mat.id)" class="text-red-600 font-bold text-xs uppercase">Eliminar</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div v-if="showEditModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 p-4">
                <div class="bg-white rounded-xl shadow-2xl w-full max-w-md p-6">
                    <h2 class="text-xl font-bold text-gray-800 border-b pb-2 mb-4">Actualizar Materia</h2>
                    <div class="space-y-3">
                        <input v-model="editForm.codigo" class="w-full border p-2 rounded text-sm" placeholder="Código" />
                        <input v-model="editForm.nombre" class="w-full border p-2 rounded text-sm" placeholder="Nombre" />
                        <textarea v-model="editForm.descripcion" class="w-full border p-2 rounded text-sm" placeholder="Descripción"></textarea>
                        <select v-model="editForm.especialidad_id" class="w-full border p-2 rounded text-sm bg-white">
                            <option v-for="esp in especialidades" :key="esp.id" :value="esp.id">{{ esp.nombre }}</option>
                        </select>
                    </div>
                    <div class="flex justify-end gap-3 mt-6">
                        <button @click="showEditModal = false" class="px-4 py-2 text-sm font-bold text-gray-500">CANCELAR</button>
                        <button @click="update" :disabled="editForm.processing" class="px-6 py-2 bg-blue-600 text-white rounded-lg font-bold text-sm">
                            ACTUALIZAR
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>