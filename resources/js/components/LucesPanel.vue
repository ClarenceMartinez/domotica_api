<template>
    <div>
        <!-- Loading -->
        <div v-if="cargando" class="bg-[#111827] border border-gray-800 rounded-xl p-12 text-center">
            <div class="w-6 h-6 border-2 border-gray-700 border-t-blue-500 rounded-full animate-spin mx-auto mb-3"></div>
            <p class="text-sm text-gray-500">Cargando dispositivos...</p>
        </div>

        <!-- Error -->
        <div v-else-if="error" class="bg-[#111827] border border-red-900/40 rounded-xl p-6 text-center">
            <p class="text-sm text-red-400">{{ error }}</p>
            <button @click="cargar" class="mt-3 text-xs text-gray-400 hover:text-white transition-colors underline">
                Reintentar
            </button>
        </div>

        <!-- Vacío -->
        <div v-else-if="dispositivos.length === 0"
             class="bg-[#111827] border border-gray-800 rounded-xl p-12 text-center">
            <svg class="w-10 h-10 mx-auto mb-3 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
            </svg>
            <p class="text-sm text-gray-600">No hay dispositivos registrados en este inmueble.</p>
        </div>

        <!-- Tabla -->
        <div v-else class="bg-[#111827] border border-gray-800 rounded-xl overflow-hidden">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-800">
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dispositivo</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                        <th class="px-6 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-800">
                    <tr v-for="d in dispositivos" :key="d.id" class="hover:bg-gray-800/40 transition-colors">
                        <!-- Nombre + icono -->
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-gray-800 border border-gray-700 flex items-center justify-center shrink-0"
                                     :class="activo(d) ? 'border-blue-800/60 bg-blue-900/20' : ''">
                                    <svg class="w-4 h-4" :class="activo(d) ? 'text-blue-400' : 'text-gray-400'"
                                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                              :d="iconos[d.tipo] ?? iconos.luz"/>
                                    </svg>
                                </div>
                                <span class="font-medium text-white">{{ d.nombre }}</span>
                            </div>
                        </td>

                        <!-- Tipo -->
                        <td class="px-6 py-4 text-gray-400">{{ labels[d.tipo] ?? d.tipo }}</td>

                        <!-- Estado -->
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center gap-1.5 text-xs font-medium px-2.5 py-1 rounded-full"
                                  :class="activo(d)
                                      ? 'bg-green-900/40 text-green-400 border border-green-800/40'
                                      : 'bg-gray-800 text-gray-500 border border-gray-700'">
                                <span class="w-1.5 h-1.5 rounded-full"
                                      :class="activo(d) ? 'bg-green-400' : 'bg-gray-600'"></span>
                                {{ capitalizar(d.estado) }}
                            </span>
                        </td>

                        <!-- Acción -->
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-end gap-4">
                                <!-- Toggle (solo dispositivos controlables) -->
                                <button v-if="esControlable(d.tipo)"
                                        @click="toggle(d)"
                                        :disabled="enviando[d.id]"
                                        class="relative inline-flex items-center h-5 w-9 rounded-full transition-colors focus:outline-none disabled:opacity-50 disabled:cursor-not-allowed"
                                        :class="activo(d) ? 'bg-blue-600' : 'bg-gray-700'">
                                    <span class="inline-block w-3.5 h-3.5 transform rounded-full bg-white transition-transform"
                                          :class="activo(d) ? 'translate-x-4' : 'translate-x-1'"></span>
                                </button>
                                <!-- Sensor / cámara: solo lectura -->
                                <span v-else class="text-xs text-gray-600">Solo lectura</span>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const props = defineProps({
    direccionId: { type: String, required: true },
})

const dispositivos = ref([])
const cargando    = ref(true)
const error       = ref(null)
const enviando    = ref({})

const labels = {
    luz:            'Luz',
    persiana:       'Persiana',
    sensor_puerta:  'Sensor de puerta',
    sensor_ventana: 'Sensor de ventana',
    camara:         'Cámara',
    calefaccion:    'Calefacción',
}

const iconos = {
    luz:            'M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z',
    persiana:       'M4 6h16M4 10h16M4 14h16M4 18h16',
    sensor_puerta:  'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6',
    sensor_ventana: 'M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2',
    camara:         'M15 10l4.553-2.069A1 1 0 0121 8.82v6.362a1 1 0 01-1.447.894L15 14M3 8a2 2 0 012-2h10a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8z',
    calefaccion:    'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z',
}

const estadosActivos    = ['encendido', 'abierto', 'activo']
const controlables      = ['luz', 'persiana', 'calefaccion']

const accionPara = {
    encendido: 'apagar',
    apagado:   'encender',
    abierto:   'cerrar',
    cerrado:   'abrir',
    activo:    'desactivar',
    inactivo:  'activar',
}

function activo(d)         { return estadosActivos.includes(d.estado) }
function esControlable(t)  { return controlables.includes(t) }
function capitalizar(s)    { return s ? s.charAt(0).toUpperCase() + s.slice(1) : '' }

async function cargar() {
    cargando.value = true
    error.value    = null
    try {
        const { data } = await axios.get(`/api/dispositivos/${props.direccionId}`)
        dispositivos.value = data.data
    } catch {
        error.value = 'No se pudo cargar los dispositivos.'
    } finally {
        cargando.value = false
    }
}

async function toggle(d) {
    const accion       = accionPara[d.estado]
    const estadoAntes  = d.estado

    // Optimistic update
    d.estado = accion === 'encender' ? 'encendido'
             : accion === 'apagar'   ? 'apagado'
             : accion === 'abrir'    ? 'abierto'
             : accion === 'cerrar'   ? 'cerrado'
             : accion === 'activar'  ? 'activo'
             : 'inactivo'

    enviando.value[d.id] = true
    try {
        const { data } = await axios.post('/api/comandos', {
            dispositivo_id: d.id,
            accion,
        })
        d.estado = data.dispositivo.estado
    } catch {
        d.estado = estadoAntes
    } finally {
        delete enviando.value[d.id]
    }
}

onMounted(cargar)
</script>
