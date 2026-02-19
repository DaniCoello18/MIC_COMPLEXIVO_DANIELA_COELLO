<script setup lang="ts">
import { Head, useForm, router } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import { ref, watch } from 'vue'
import { type BreadcrumbItem } from '@/types'

interface Edificio {
    id: number
    codigo: string
    nombre: string
    direccion: string
}

const props = defineProps<{
    edificios: Edificio[]
    filters?: {
        search?: string
    }
}>()

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Gestión de Edificios', href: '/edificios' }
]

// --- BÚSQUEDA REACTIVA ---
const search = ref(props.filters?.search ?? '')

watch(search, (value) => {
    router.get('/edificios', { search: value }, {
        preserveState: true,
        replace: true,
        preserveScroll: true
    })
})

// --- CRUD: CREAR ---
const form = useForm({
    codigo: '',
    nombre: '',
    direccion: ''
})

const submit = () => {
    form.post('/edificios', {
        onSuccess: () => form.reset()
    })
}

// --- CRUD: EDITAR ---
const editForm = useForm({
    id: 0,
    codigo: '',
    nombre: '',
    direccion: ''
})

const showEditModal = ref(false)

const startEdit = (edificio: Edificio) => {
    editForm.clearErrors()
    editForm.id = edificio.id
    editForm.codigo = edificio.codigo
    editForm.nombre = edificio.nombre
    editForm.direccion = edificio.direccion
    showEditModal.value = true
}

const update = () => {
    editForm.put(`/edificios/${editForm.id}`, {
        onSuccess: () => {
            showEditModal.value = false
            editForm.reset()
        }
    })
}

// --- CRUD: ELIMINAR ---
const eliminar = (id: number) => {
    if (confirm('¿Estás seguro de eliminar este edificio?')) {
        router.delete(`/edificios/${id}`)
    }
}
</script>

<template>
    <Head title="Edificios" />

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
                        placeholder="Buscar por código, nombre o dirección..."
                        class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition-all"
                    />
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="md:col-span-1 bg-white p-6 rounded-lg shadow border border-gray-200 h-fit">
                    <h2 class="text-xl font-bold mb-4 text-gray-700">Nuevo Edificio</h2>
                    <form @submit.prevent="submit" class="space-y-4">
                        <div>
                            <input v-model="form.codigo" placeholder="Código (ej: ED-01)" :class="{'border-red-500 ring-1 ring-red-200': form.errors.codigo}" class="w-full border p-2 rounded focus:ring-2 focus:ring-blue-500 outline-none" />
                            <p v-if="form.errors.codigo" class="text-red-500 text-xs mt-1 font-semibold">{{ form.errors.codigo }}</p>
                        </div>

                        <div>
                            <input v-model="form.nombre" placeholder="Nombre del Edificio" :class="{'border-red-500': form.errors.nombre}" class="w-full border p-2 rounded outline-none focus:ring-2 focus:ring-blue-500" />
                            <p v-if="form.errors.nombre" class="text-red-500 text-xs mt-1">{{ form.errors.nombre }}</p>
                        </div>

                        <div>
                            <input v-model="form.direccion" placeholder="Dirección / Ubicación" :class="{'border-red-500': form.errors.direccion}" class="w-full border p-2 rounded outline-none focus:ring-2 focus:ring-blue-500" />
                            <p v-if="form.errors.direccion" class="text-red-500 text-xs mt-1">{{ form.errors.direccion }}</p>
                        </div>

                        <button type="submit" :disabled="form.processing" class="w-full bg-blue-600 text-white p-2 rounded font-semibold hover:bg-blue-700 transition disabled:opacity-50 shadow-md">
                            {{ form.processing ? 'Procesando...' : 'Registrar Edificio' }}
                        </button>
                    </form>
                </div>

                <div class="md:col-span-2 bg-white rounded-lg shadow border border-gray-200 overflow-hidden">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50 border-b">
                            <tr>
                                <th class="px-4 py-3 text-sm font-bold text-gray-600">Edificio</th>
                                <th class="px-4 py-3 text-sm font-bold text-gray-600 text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <tr v-for="ed in edificios" :key="ed.id" class="hover:bg-gray-50 transition">
                                <td class="px-4 py-3">
                                    <div class="font-bold text-blue-600 text-sm font-mono">{{ ed.codigo }}</div>
                                    <div class="text-sm font-bold text-gray-800">{{ ed.nombre }}</div>
                                    <div class="text-xs text-gray-500">{{ ed.direccion }}</div>
                                </td>
                                <td class="px-4 py-3 text-right space-x-3">
                                    <button @click="startEdit(ed)" class="text-blue-600 hover:text-blue-800 font-bold text-xs uppercase">Editar</button>
                                    <button @click="eliminar(ed.id)" class="text-red-600 hover:text-red-800 font-bold text-xs uppercase">Eliminar</button>
                                </td>
                            </tr>
                            <tr v-if="edificios.length === 0">
                                <td colspan="2" class="px-4 py-10 text-center text-gray-400 italic">No se encontraron edificios.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div v-if="showEditModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 p-4">
                <div class="bg-white rounded-xl shadow-2xl w-full max-w-md overflow-hidden">
                    <div class="p-6 space-y-4">
                        <h2 class="text-xl font-bold text-gray-800 border-b pb-2">Actualizar Edificio</h2>
                        
                        <div class="space-y-3">
                            <div>
                                <label class="text-[10px] font-bold text-gray-400 uppercase">Código</label>
                                <input v-model="editForm.codigo" :class="{'border-red-500': editForm.errors.codigo}" class="w-full border p-2 rounded text-sm mt-1 focus:ring-2 focus:ring-blue-500 outline-none" />
                            </div>
                            
                            <div>
                                <label class="text-[10px] font-bold text-gray-400 uppercase">Nombre</label>
                                <input v-model="editForm.nombre" :class="{'border-red-500': editForm.errors.nombre}" class="w-full border p-2 rounded text-sm mt-1 focus:ring-2 focus:ring-blue-500 outline-none" />
                            </div>

                            <div>
                                <label class="text-[10px] font-bold text-gray-400 uppercase">Dirección</label>
                                <input v-model="editForm.direccion" class="w-full border p-2 rounded text-sm mt-1 focus:ring-2 focus:ring-blue-500 outline-none" />
                            </div>
                        </div>

                        <div class="flex justify-end gap-3 mt-6">
                            <button @click="showEditModal = false" class="px-4 py-2 text-sm font-bold text-gray-500 hover:bg-gray-50 rounded">CANCELAR</button>
                            <button @click="update" :disabled="editForm.processing" class="px-6 py-2 bg-blue-600 text-white rounded-lg font-bold text-sm hover:bg-blue-700 shadow-lg">
                                {{ editForm.processing ? 'GUARDANDO...' : 'ACTUALIZAR' }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </AppLayout>
</template>