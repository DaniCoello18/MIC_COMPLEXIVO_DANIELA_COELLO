<script setup lang="ts">
import { Head, useForm, router } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import { ref, watch } from 'vue'
import { type BreadcrumbItem } from '@/types'

interface Alumno { id: number; nombre: string; apellido: string }
interface Materia { id: number; nombre: string; codigo: string }
interface Matricula {
    id: number; codigo: string; fecha_matricula: string;
    alumno_id: number; materia_id: number;
}

const props = defineProps<{
    matriculas: Matricula[]
    alumnos: Alumno[]
    materias: Materia[]
    filters?: { search?: string }
}>()

const breadcrumbs: BreadcrumbItem[] = [{ title: 'Control de Matrículas', href: '/matriculas' }]

// --- BUSCADOR ---
const search = ref(props.filters?.search ?? '')
watch(search, (value) => {
    router.get('/matriculas', { search: value }, { preserveState: true, replace: true, preserveScroll: true })
})

// --- FORMULARIO CREAR ---
const form = useForm({
    codigo: '',
    fecha_matricula: new Date().toISOString().substr(0, 10),
    alumno_id: '' as any,
    materia_id: '' as any
})

const submit = () => form.post('/matriculas', { onSuccess: () => form.reset() })

// --- FORMULARIO EDITAR ---
const editForm = useForm({
    id: 0, codigo: '', fecha_matricula: '', alumno_id: 0, materia_id: 0
})
const showEditModal = ref(false)

const startEdit = (m: Matricula) => {
    editForm.id = m.id; editForm.codigo = m.codigo;
    editForm.fecha_matricula = m.fecha_matricula;
    editForm.alumno_id = m.alumno_id; editForm.materia_id = m.materia_id;
    showEditModal.value = true
}

const update = () => editForm.put(`/matriculas/${editForm.id}`, { onSuccess: () => showEditModal.value = false })

// --- HELPERS ---
const getAlumnoNombre = (id: number) => {
    const a = props.alumnos.find(x => x.id === id)
    return a ? `${a.nombre} ${a.apellido}` : 'Desconocido'
}
const getMateriaNombre = (id: number) => props.materias.find(x => x.id === id)?.nombre || 'Desconocida'
</script>

<template>
    <Head title="Matrículas" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6 space-y-6 max-w-7xl mx-auto">
            
            <div class="bg-white p-4 rounded-lg shadow border">
                <input v-model="search" type="text" placeholder="Buscar matrícula..." class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none" />
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white p-6 rounded-lg shadow border h-fit">
                    <h2 class="text-xl font-bold mb-4 text-gray-700">Nueva Matrícula</h2>
                    <form @submit.prevent="submit" class="space-y-4">
                        <input v-model="form.codigo" placeholder="Código de Matrícula" class="w-full border p-2 rounded" />
                        <input type="date" v-model="form.fecha_matricula" class="w-full border p-2 rounded" />
                        
                        <select v-model="form.alumno_id" class="w-full border p-2 rounded bg-white">
                            <option value="">Seleccione Alumno</option>
                            <option v-for="a in alumnos" :key="a.id" :value="a.id">{{ a.nombre }} {{ a.apellido }}</option>
                        </select>

                        <select v-model="form.materia_id" class="w-full border p-2 rounded bg-white">
                            <option value="">Seleccione Materia</option>
                            <option v-for="mat in materias" :key="mat.id" :value="mat.id">{{ mat.nombre }}</option>
                        </select>

                        <button :disabled="form.processing" class="w-full bg-indigo-600 text-white p-2 rounded font-bold hover:bg-indigo-700">
                            Matricular Alumno
                        </button>
                    </form>
                </div>

                <div class="md:col-span-2 bg-white rounded-lg shadow border overflow-hidden">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50 border-b">
                            <tr>
                                <th class="px-4 py-3 text-sm font-bold text-gray-600">Matrícula / Alumno</th>
                                <th class="px-4 py-3 text-sm font-bold text-gray-600">Materia</th>
                                <th class="px-4 py-3 text-right text-sm font-bold text-gray-600">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            <tr v-for="m in matriculas" :key="m.id" class="hover:bg-gray-50">
                                <td class="px-4 py-3">
                                    <div class="font-bold text-indigo-600">{{ m.codigo }}</div>
                                    <div class="text-sm font-bold">{{ getAlumnoNombre(m.alumno_id) }}</div>
                                    <div class="text-xs text-gray-400">Fecha: {{ m.fecha_matricula }}</div>
                                </td>
                                <td class="px-4 py-3 text-sm">{{ getMateriaNombre(m.materia_id) }}</td>
                                <td class="px-4 py-3 text-right space-x-2">
                                    <button @click="startEdit(m)" class="text-blue-600 font-bold text-xs">EDITAR</button>
                                    <button @click="router.delete(`/matriculas/${m.id}`)" class="text-red-600 font-bold text-xs">ELIMINAR</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div v-if="showEditModal" class="fixed inset-0 bg-black/50 flex items-center justify-center p-4 z-50">
                <div class="bg-white rounded-xl shadow-2xl w-full max-w-md p-6">
                    <h2 class="text-xl font-bold mb-4 border-b pb-2">Editar Matrícula</h2>
                    <div class="space-y-3">
                        <input v-model="editForm.codigo" class="w-full border p-2 rounded" placeholder="Código" />
                        <input type="date" v-model="editForm.fecha_matricula" class="w-full border p-2 rounded" />
                        <select v-model="editForm.alumno_id" class="w-full border p-2 rounded">
                            <option v-for="a in alumnos" :key="a.id" :value="a.id">{{ a.nombre }} {{ a.apellido }}</option>
                        </select>
                        <select v-model="editForm.materia_id" class="w-full border p-2 rounded">
                            <option v-for="mat in materias" :key="mat.id" :value="mat.id">{{ mat.nombre }}</option>
                        </select>
                    </div>
                    <div class="flex justify-end gap-3 mt-6">
                        <button @click="showEditModal = false" class="text-gray-500 font-bold">CANCELAR</button>
                        <button @click="update" class="bg-indigo-600 text-white px-4 py-2 rounded font-bold">GUARDAR</button>
                    </div>
                </div>
            </div>

        </div>
    </AppLayout>
</template>