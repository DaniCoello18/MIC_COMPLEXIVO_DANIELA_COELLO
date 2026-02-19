<script setup lang="ts">
import { Head, useForm, router } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import { ref, watch } from 'vue'
import { type BreadcrumbItem } from '@/types'

interface Especialidad {
    id: number
    nombre: string
}

interface Profesor {
    id: number
    cedula: string
    nombre: string
    apellido: string
    correo: string
    especialidad_id: number
}

const props = defineProps<{
    profesores: Profesor[]
    especialidades: Especialidad[]
    filters?: {
        search?: string
    }
}>()

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Gestión de Profesores', href: '/profesores' }
]

// --- BÚSQUEDA REACTIVA ---
const search = ref(props.filters?.search ?? '')

watch(search, (value) => {
    router.get('/profesores', { search: value }, {
        preserveState: true,
        replace: true,
        preserveScroll: true
    })
})

// --- CRUD: CREAR ---
const form = useForm({
    cedula: '',
    nombre: '',
    apellido: '',
    correo: '',
    especialidad_id: '' as string | number
})

const submit = () => {
    form.post('/profesores', {
        onSuccess: () => form.reset()
    })
}

// --- CRUD: EDITAR ---
const editForm = useForm({
    id: 0,
    cedula: '',
    nombre: '',
    apellido: '',
    correo: '',
    especialidad_id: 0 as string | number
})

const showEditModal = ref(false)

const startEdit = (profesor: Profesor) => {
    editForm.clearErrors()
    editForm.id = profesor.id
    editForm.cedula = profesor.cedula
    editForm.nombre = profesor.nombre
    editForm.apellido = profesor.apellido
    editForm.correo = profesor.correo
    editForm.especialidad_id = profesor.especialidad_id
    showEditModal.value = true
}

const update = () => {
    editForm.put(`/profesores/${editForm.id}`, {
        onSuccess: () => {
            showEditModal.value = false
            editForm.reset()
        }
    })
}

// --- CRUD: ELIMINAR ---
const eliminar = (id: number) => {
    if (confirm('¿Estás seguro de eliminar este profesor?')) {
        router.delete(`/profesores/${id}`)
    }
}

// Helper para mostrar el nombre de la especialidad en la tabla
const getEspecialidadNombre = (id: number) => {
    return props.especialidades.find(e => e.id === id)?.nombre ?? 'Sin especialidad'
}
</script>

<template>
    <Head title="Profesores" />

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
                        placeholder="Filtrar por nombre, cédula, correo..."
                        class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition-all"
                    />
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="md:col-span-1 bg-white p-6 rounded-lg shadow border border-gray-200 h-fit">
                    <h2 class="text-xl font-bold mb-4 text-gray-700">Nuevo Profesor</h2>
                    <form @submit.prevent="submit" class="space-y-4">
                        <div>
                            <input v-model="form.cedula" placeholder="Cédula" :class="{'border-red-500 ring-1 ring-red-200': form.errors.cedula}" class="w-full border p-2 rounded focus:ring-2 focus:ring-blue-500 outline-none" />
                            <p v-if="form.errors.cedula" class="text-red-500 text-xs mt-1 font-semibold">{{ form.errors.cedula }}</p>
                        </div>

                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <input v-model="form.nombre" placeholder="Nombre" :class="{'border-red-500': form.errors.nombre}" class="w-full border p-2 rounded outline-none focus:ring-2 focus:ring-blue-500" />
                                <p v-if="form.errors.nombre" class="text-red-500 text-xs mt-1">{{ form.errors.nombre }}</p>
                            </div>
                            <div>
                                <input v-model="form.apellido" placeholder="Apellido" :class="{'border-red-500': form.errors.apellido}" class="w-full border p-2 rounded outline-none focus:ring-2 focus:ring-blue-500" />
                                <p v-if="form.errors.apellido" class="text-red-500 text-xs mt-1">{{ form.errors.apellido }}</p>
                            </div>
                        </div>

                        <div>
                            <input v-model="form.correo" placeholder="Correo electrónico" :class="{'border-red-500': form.errors.correo}" class="w-full border p-2 rounded outline-none focus:ring-2 focus:ring-blue-500" />
                            <p v-if="form.errors.correo" class="text-red-500 text-xs mt-1">{{ form.errors.correo }}</p>
                        </div>

                        <div>
                            <select v-model="form.especialidad_id" :class="{'border-red-500': form.errors.especialidad_id}" class="w-full border p-2 rounded outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                                <option value="">Seleccione Especialidad</option>
                                <option v-for="esp in especialidades" :key="esp.id" :value="esp.id">
                                    {{ esp.nombre }}
                                </option>
                            </select>
                            <p v-if="form.errors.especialidad_id" class="text-red-500 text-xs mt-1">{{ form.errors.especialidad_id }}</p>
                        </div>

                        <button type="submit" :disabled="form.processing" class="w-full bg-blue-600 text-white p-2 rounded font-semibold hover:bg-blue-700 transition disabled:opacity-50 shadow-md">
                            {{ form.processing ? 'Procesando...' : 'Registrar Profesor' }}
                        </button>
                    </form>
                </div>

                <div class="md:col-span-2 bg-white rounded-lg shadow border border-gray-200 overflow-hidden">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50 border-b">
                            <tr>
                                <th class="px-4 py-3 text-sm font-bold text-gray-600">Profesor</th>
                                <th class="px-4 py-3 text-sm font-bold text-gray-600">Especialidad</th>
                                <th class="px-4 py-3 text-sm font-bold text-gray-600 text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <tr v-for="prof in profesores" :key="prof.id" class="hover:bg-gray-50 transition">
                                <td class="px-4 py-3">
                                    <div class="font-bold text-gray-900 text-sm">{{ prof.nombre }} {{ prof.apellido }}</div>
                                    <div class="text-xs text-gray-500">{{ prof.cedula }} | {{ prof.correo }}</div>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="px-2 py-1 bg-blue-50 text-blue-700 rounded text-xs font-bold uppercase">
                                        {{ getEspecialidadNombre(prof.especialidad_id) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-right space-x-3">
                                    <button @click="startEdit(prof)" class="text-blue-600 hover:text-blue-800 font-bold text-xs uppercase">Editar</button>
                                    <button @click="eliminar(prof.id)" class="text-red-600 hover:text-red-800 font-bold text-xs uppercase">Eliminar</button>
                                </td>
                            </tr>
                            <tr v-if="profesores.length === 0">
                                <td colspan="3" class="px-4 py-10 text-center text-gray-400 italic">No hay profesores registrados.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div v-if="showEditModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 p-4">
                <div class="bg-white rounded-xl shadow-2xl w-full max-w-md overflow-hidden">
                    <div class="p-6 space-y-4">
                        <h2 class="text-xl font-bold text-gray-800 border-b pb-2">Editar Profesor</h2>
                        
                        <div class="space-y-3">
                            <div>
                                <label class="text-[10px] font-bold text-gray-400 uppercase">Cédula</label>
                                <input v-model="editForm.cedula" :class="{'border-red-500': editForm.errors.cedula}" class="w-full border p-2 rounded text-sm mt-1 focus:ring-2 focus:ring-blue-500 outline-none" />
                            </div>
                            
                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <label class="text-[10px] font-bold text-gray-400 uppercase">Nombre</label>
                                    <input v-model="editForm.nombre" class="w-full border p-2 rounded text-sm mt-1 focus:ring-2 focus:ring-blue-500 outline-none" />
                                </div>
                                <div>
                                    <label class="text-[10px] font-bold text-gray-400 uppercase">Apellido</label>
                                    <input v-model="editForm.apellido" class="w-full border p-2 rounded text-sm mt-1 focus:ring-2 focus:ring-blue-500 outline-none" />
                                </div>
                            </div>

                            <div>
                                <label class="text-[10px] font-bold text-gray-400 uppercase">Correo</label>
                                <input v-model="editForm.correo" class="w-full border p-2 rounded text-sm mt-1 focus:ring-2 focus:ring-blue-500 outline-none" />
                            </div>

                            <div>
                                <label class="text-[10px] font-bold text-gray-400 uppercase">Especialidad</label>
                                <select v-model="editForm.especialidad_id" class="w-full border p-2 rounded text-sm mt-1 focus:ring-2 focus:ring-blue-500 outline-none bg-white">
                                    <option v-for="esp in especialidades" :key="esp.id" :value="esp.id">
                                        {{ esp.nombre }}
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="flex justify-end gap-3 mt-6">
                            <button @click="showEditModal = false" class="px-4 py-2 text-sm font-bold text-gray-500 hover:bg-gray-50 rounded">CANCELAR</button>
                            <button @click="update" :disabled="editForm.processing" class="px-6 py-2 bg-blue-600 text-white rounded-lg font-bold text-sm hover:bg-blue-700 shadow-lg transition">
                                {{ editForm.processing ? 'GUARDANDO...' : 'ACTUALIZAR PROFESOR' }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </AppLayout>
</template>