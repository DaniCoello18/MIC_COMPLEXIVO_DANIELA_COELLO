<script setup lang="ts">
import { Head, useForm, router } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import { ref, watch } from 'vue'
import { type BreadcrumbItem } from '@/types'

interface Materia { id: number; nombre: string; codigo: string }
interface Edificio { id: number; nombre: string }
interface Horario {
    id: number; codigo: string; dia_semana: string; 
    hora_inicio: string; duracion: string; 
    materia_id: number; edificio_id: number;
}

const props = defineProps<{
    horarios: Horario[]
    materias: Materia[]
    edificios: Edificio[]
    filters?: { search?: string }
}>()

const breadcrumbs: BreadcrumbItem[] = [{ title: 'Gestión de Horarios', href: '/horarios' }]

// --- BÚSQUEDA ---
const search = ref(props.filters?.search ?? '')
watch(search, (value) => {
    router.get('/horarios', { search: value }, { preserveState: true, replace: true, preserveScroll: true })
})

const dias = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo']

// --- CRUD: CREAR ---
const form = useForm({
    codigo: '', dia_semana: '', hora_inicio: '', 
    duracion: '', materia_id: '' as any, edificio_id: '' as any
})

const submit = () => form.post('/horarios', { onSuccess: () => form.reset() })

// --- CRUD: EDITAR ---
const editForm = useForm({
    id: 0, codigo: '', dia_semana: '', hora_inicio: '', 
    duracion: '', materia_id: 0, edificio_id: 0
})
const showEditModal = ref(false)

const startEdit = (h: Horario) => {
    editForm.id = h.id; editForm.codigo = h.codigo;
    editForm.dia_semana = h.dia_semana; editForm.hora_inicio = h.hora_inicio;
    editForm.duracion = h.duracion; editForm.materia_id = h.materia_id;
    editForm.edificio_id = h.edificio_id;
    showEditModal.value = true
}

const update = () => editForm.put(`/horarios/${editForm.id}`, { onSuccess: () => showEditModal.value = false })

// --- HELPERS ---
const getMateria = (id: number) => props.materias.find(m => m.id === id)?.nombre || 'N/A'
const getEdificio = (id: number) => props.edificios.find(e => e.id === id)?.nombre || 'N/A'
</script>

<template>
    <Head title="Horarios" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6 space-y-6 max-w-7xl mx-auto">
            <div class="bg-white p-4 rounded-lg shadow border"><input v-model="search" type="text" placeholder="Buscar horario..." class="w-full p-2 border rounded-lg outline-none focus:ring-2 focus:ring-blue-500" /></div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white p-6 rounded-lg shadow border h-fit">
                    <h2 class="text-xl font-bold mb-4">Nuevo Horario</h2>
                    <form @submit.prevent="submit" class="space-y-3">
                        <input v-model="form.codigo" placeholder="Código" class="w-full border p-2 rounded" />
                        <select v-model="form.dia_semana" class="w-full border p-2 rounded">
                            <option value="">Día</option>
                            <option v-for="d in dias" :key="d" :value="d">{{ d }}</option>
                        </select>
                        <div class="grid grid-cols-2 gap-2">
                            <input type="time" v-model="form.hora_inicio" class="border p-2 rounded" />
                            <input v-model="form.duracion" placeholder="Duración (ej: 2h)" class="border p-2 rounded" />
                        </div>
                        <select v-model="form.materia_id" class="w-full border p-2 rounded">
                            <option value="">Materia</option>
                            <option v-for="m in materias" :key="m.id" :value="m.id">{{ m.nombre }}</option>
                        </select>
                        <select v-model="form.edificio_id" class="w-full border p-2 rounded">
                            <option value="">Edificio</option>
                            <option v-for="e in edificios" :key="e.id" :value="e.id">{{ e.nombre }}</option>
                        </select>
                        <button class="w-full bg-blue-600 text-white p-2 rounded font-bold">Registrar</button>
                    </form>
                </div>

                <div class="md:col-span-2 bg-white rounded-lg shadow border overflow-hidden">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50 border-b">
                            <tr><th class="px-4 py-3">Horario / Materia</th><th class="px-4 py-3">Ubicación</th><th class="px-4 py-3 text-right">Acciones</th></tr>
                        </thead>
                        <tbody class="divide-y">
                            <tr v-for="h in horarios" :key="h.id" class="hover:bg-gray-50">
                                <td class="px-4 py-3">
                                    <div class="font-bold text-sm">{{ getMateria(h.materia_id) }}</div>
                                    <div class="text-xs text-gray-500">{{ h.dia_semana }} | {{ h.hora_inicio }} ({{ h.duracion }})</div>
                                </td>
                                <td class="px-4 py-3 text-sm">{{ getEdificio(h.edificio_id) }}</td>
                                <td class="px-4 py-3 text-right space-x-2">
                                    <button @click="startEdit(h)" class="text-blue-600 font-bold text-xs uppercase">Editar</button>
                                    <button @click="router.delete(`/horarios/${h.id}`)" class="text-red-600 font-bold text-xs uppercase">Eliminar</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div v-if="showEditModal" class="fixed inset-0 bg-black/50 flex items-center justify-center p-4 z-50">
                <div class="bg-white p-6 rounded-xl w-full max-w-md">
                    <h2 class="text-xl font-bold mb-4">Editar Horario</h2>
                    <div class="space-y-3">
                        <input v-model="editForm.codigo" class="w-full border p-2 rounded" />
                        <select v-model="editForm.dia_semana" class="w-full border p-2 rounded">
                            <option v-for="d in dias" :key="d" :value="d">{{ d }}</option>
                        </select>
                        <input type="time" v-model="editForm.hora_inicio" class="w-full border p-2 rounded" />
                        <input v-model="editForm.duracion" class="w-full border p-2 rounded" />
                        <select v-model="editForm.materia_id" class="w-full border p-2 rounded">
                            <option v-for="m in materias" :key="m.id" :value="m.id">{{ m.nombre }}</option>
                        </select>
                        <select v-model="editForm.edificio_id" class="w-full border p-2 rounded">
                            <option v-for="e in edificios" :key="e.id" :value="e.id">{{ e.nombre }}</option>
                        </select>
                    </div>
                    <div class="flex justify-end gap-2 mt-4">
                        <button @click="showEditModal = false" class="p-2 text-gray-500">Cancelar</button>
                        <button @click="update" class="bg-blue-600 text-white px-4 py-2 rounded">Guardar</button>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>