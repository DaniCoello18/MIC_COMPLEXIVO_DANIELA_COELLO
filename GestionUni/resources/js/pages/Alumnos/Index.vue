<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import { ref } from 'vue'
import { type BreadcrumbItem } from '@/types'

interface Alumno {
    id: number
    cedula: string
    nombre: string
    apellido: string
    fecha_nacimiento: string
    direccion: string
    correo: string
}

const props = defineProps<{
    alumnos: Alumno[]
    filters?: {
        search?: string
    }
}>()

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Alumnos', href: '/alumnos' }
]

const form = useForm({
    cedula: '',
    nombre: '',
    apellido: '',
    fecha_nacimiento: '',
    direccion: '',
    correo: ''
})

const submit = () => {
    form.post('/alumnos', {
        onSuccess: () => form.reset()
    })
}

const editForm = useForm({
    id: 0,
    cedula: '',
    nombre: '',
    apellido: '',
    fecha_nacimiento: '',
    direccion: '',
    correo: ''
})

const showEditModal = ref(false)

const startEdit = (alumno: Alumno) => {
    editForm.clearErrors()
    Object.assign(editForm, alumno)
    showEditModal.value = true
}

const update = () => {
    editForm.put(`/alumnos/${editForm.id}`, {
        onSuccess: () => {
            showEditModal.value = false
            editForm.reset()
        }
    })
}

const eliminar = (id: number) => {
    if (confirm('¿Eliminar alumno?')) {
        editForm.delete(`/alumnos/${id}`)
    }
}

const searchForm = useForm({
    search: props.filters?.search ?? ''
})

const buscar = () => {
    searchForm.get('/alumnos', {
        preserveState: true,
        replace: true
    })
}

const limpiarBusqueda = () => {
    searchForm.reset()
    searchForm.get('/alumnos', { replace: true })
}
</script>

<template>
    <Head title="Alumnos" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6 space-y-8">

            <div class="bg-white p-4 rounded shadow">
                <h2 class="text-lg font-bold mb-4">Nuevo Alumno</h2>

                <form @submit.prevent="submit" class="grid grid-cols-2 gap-4">
                    <div>
                        <input v-model="form.cedula" placeholder="Cédula" class="border p-2 rounded w-full" />
                        <div v-if="form.errors.cedula" class="text-red-500 text-sm">
                            {{ form.errors.cedula }}
                        </div>
                    </div>

                    <div>
                        <input v-model="form.nombre" placeholder="Nombre" class="border p-2 rounded w-full" />
                        <div v-if="form.errors.nombre" class="text-red-500 text-sm">
                            {{ form.errors.nombre }}
                        </div>
                    </div>

                    <div>
                        <input v-model="form.apellido" placeholder="Apellido" class="border p-2 rounded w-full" />
                        <div v-if="form.errors.apellido" class="text-red-500 text-sm">
                            {{ form.errors.apellido }}
                        </div>
                    </div>

                    <div>
                        <input type="date" v-model="form.fecha_nacimiento" class="border p-2 rounded w-full" />
                    </div>

                    <div>
                        <input v-model="form.direccion" placeholder="Dirección" class="border p-2 rounded w-full" />
                    </div>

                    <div>
                        <input v-model="form.correo" placeholder="Correo" class="border p-2 rounded w-full" />
                        <div v-if="form.errors.correo" class="text-red-500 text-sm">
                            {{ form.errors.correo }}
                        </div>
                    </div>

                    <button type="submit"
                        class="col-span-2 bg-blue-500 text-white p-2 rounded hover:bg-blue-600"
                        :disabled="form.processing">
                        Guardar
                    </button>
                </form>
            </div>

            <div class="bg-white p-4 rounded shadow">
                <h2 class="text-lg font-bold mb-4">Buscar</h2>

                <div class="flex gap-4">
                    <input
                        v-model="searchForm.search"
                        @input="buscar"
                        placeholder="Buscar..."
                        class="border p-2 rounded w-full"
                    />

                    <button
                        @click="limpiarBusqueda"
                        class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                        Limpiar
                    </button>
                </div>
            </div>

            <div class="bg-white p-4 rounded shadow">
                <h2 class="text-lg font-bold mb-4">Lista de Alumnos</h2>

                <table class="w-full border">
                    <thead>
                        <tr class="bg-gray-200">
                            <th class="p-2 border">Cédula</th>
                            <th class="p-2 border">Nombre</th>
                            <th class="p-2 border">Apellido</th>
                            <th class="p-2 border">Correo</th>
                            <th class="p-2 border">Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr v-for="al in alumnos" :key="al.id">
                            <td class="border p-2">{{ al.cedula }}</td>
                            <td class="border p-2">{{ al.nombre }}</td>
                            <td class="border p-2">{{ al.apellido }}</td>
                            <td class="border p-2">{{ al.correo }}</td>
                            <td class="border p-2 space-x-2">
                                <button @click="startEdit(al)"
                                    class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600">
                                    Editar
                                </button>

                                <button @click="eliminar(al.id)"
                                    class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">
                                    Eliminar
                                </button>
                            </td>
                        </tr>

                        <tr v-if="alumnos.length === 0">
                            <td colspan="5" class="text-center p-4">
                                No hay resultados
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div v-if="showEditModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
                <div class="bg-white p-6 rounded shadow w-96 space-y-4">
                    <h2 class="text-lg font-bold">Editar Alumno</h2>

                    <div>
                        <input v-model="editForm.cedula" class="border p-2 rounded w-full" />
                        <div v-if="editForm.errors.cedula" class="text-red-500 text-sm">
                            {{ editForm.errors.cedula }}
                        </div>
                    </div>

                    <div>
                        <input v-model="editForm.nombre" class="border p-2 rounded w-full" />
                        <div v-if="editForm.errors.nombre" class="text-red-500 text-sm">
                            {{ editForm.errors.nombre }}
                        </div>
                    </div>

                    <div>
                        <input v-model="editForm.apellido" class="border p-2 rounded w-full" />
                        <div v-if="editForm.errors.apellido" class="text-red-500 text-sm">
                            {{ editForm.errors.apellido }}
                        </div>
                    </div>

                    <div>
                        <input v-model="editForm.correo" class="border p-2 rounded w-full" />
                        <div v-if="editForm.errors.correo" class="text-red-500 text-sm">
                            {{ editForm.errors.correo }}
                        </div>
                    </div>

                    <div class="flex justify-end gap-2">
                        <button @click="showEditModal = false"
                            class="bg-gray-500 text-white px-4 py-2 rounded">
                            Cancelar
                        </button>

                        <button @click="update"
                            class="bg-blue-500 text-white px-4 py-2 rounded"
                            :disabled="editForm.processing">
                            Actualizar
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </AppLayout>
</template>
