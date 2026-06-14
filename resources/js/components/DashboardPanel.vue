<template>
    <div>

        <!-- Selector bar -->
        <div class="flex items-center gap-3 mb-6">
            <div class="relative">
                <select v-model="clienteId" @change="onClienteChange"
                        class="appearance-none bg-[#111827] border border-gray-700 text-sm text-white rounded-lg px-4 py-2.5 pr-9 focus:outline-none focus:border-blue-500 cursor-pointer min-w-44">
                    <option value="">Seleccionar cliente</option>
                    <option v-for="c in clientes" :key="c.id" :value="c.id">{{ c.nombre }}</option>
                </select>
                <svg class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-500"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </div>

            <div class="relative">
                <select v-model="direccionId" @change="onDireccionChange"
                        :disabled="!clienteId || direccionesDelCliente.length === 0"
                        class="appearance-none bg-[#111827] border border-gray-700 text-sm text-white rounded-lg px-4 py-2.5 pr-9 focus:outline-none focus:border-blue-500 cursor-pointer min-w-52 disabled:opacity-40 disabled:cursor-not-allowed">
                    <option value="">Seleccionar inmueble</option>
                    <option v-for="d in direccionesDelCliente" :key="d.id" :value="d.id">{{ d.alias }}</option>
                </select>
                <svg class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-500"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </div>
        </div>

        <!-- Empty state -->
        <div v-if="!direccionId" class="flex flex-col items-center justify-center py-24 text-center">
            <div class="w-14 h-14 bg-[#111827] border border-gray-800 rounded-2xl flex items-center justify-center mb-4">
                <svg class="w-7 h-7 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
            </div>
            <p class="text-sm text-gray-500">Seleccioná un cliente y un inmueble para ver el panel.</p>
        </div>

        <!-- Dashboard content -->
        <template v-else>

            <!-- Loading overlay -->
            <div v-if="cargando" class="flex items-center justify-center py-24">
                <div class="w-6 h-6 border-2 border-gray-700 border-t-blue-500 rounded-full animate-spin"></div>
            </div>

            <template v-else>

                <!-- Stats row -->
                <div class="grid grid-cols-4 gap-4 mb-6">

                    <!-- Clima exterior (placeholder) -->
                    <div class="bg-[#111827] border border-gray-800 rounded-xl p-5 relative overflow-hidden">
                        <p class="text-xs font-semibold text-blue-400 uppercase tracking-wider mb-3">Clima exterior</p>
                        <div v-if="clima" class="space-y-1">
                            <div class="flex items-end gap-2">
                                <span class="text-3xl font-bold text-white">{{ clima.temp }}°C</span>
                            </div>
                            <p class="text-sm text-gray-400">{{ clima.descripcion }}</p>
                            <p class="text-xs text-gray-600 mt-2">
                                Humedad: {{ clima.humedad }}% · Viento: {{ clima.viento }} km/h
                            </p>
                        </div>
                        <div v-else class="space-y-2">
                            <div class="flex items-end gap-2">
                                <span class="text-3xl font-bold text-gray-700">—°C</span>
                            </div>
                            <p class="text-xs text-gray-700">Humedad: — · Viento: —</p>
                            <span class="inline-block text-[10px] font-medium bg-gray-800 text-gray-600 px-2 py-0.5 rounded-full mt-1">
                                Próximamente
                            </span>
                        </div>
                        <svg class="absolute right-4 bottom-4 w-10 h-10 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                  d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"/>
                        </svg>
                    </div>

                    <!-- Consumo eléctrico (placeholder) -->
                    <div class="bg-[#111827] border border-gray-800 rounded-xl p-5 relative overflow-hidden">
                        <p class="text-xs font-semibold text-green-400 uppercase tracking-wider mb-3">Consumo eléctrico</p>
                        <div v-if="consumo">
                            <p class="text-3xl font-bold text-white">{{ consumo.actual }} kW</p>
                            <p class="text-xs text-gray-500 mt-1">Hoy: {{ consumo.hoy }} kWh</p>
                        </div>
                        <div v-else class="space-y-2">
                            <p class="text-3xl font-bold text-gray-700">— kW</p>
                            <p class="text-xs text-gray-700">Hoy: — kWh</p>
                            <span class="inline-block text-[10px] font-medium bg-gray-800 text-gray-600 px-2 py-0.5 rounded-full mt-1">
                                Próximamente
                            </span>
                        </div>
                        <svg class="absolute right-4 bottom-4 w-10 h-10 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>

                    <!-- Temperatura interior (placeholder) -->
                    <div class="bg-[#111827] border border-gray-800 rounded-xl p-5 relative overflow-hidden">
                        <p class="text-xs font-semibold text-orange-400 uppercase tracking-wider mb-3">Temperatura interior</p>
                        <div v-if="temperatura">
                            <p class="text-3xl font-bold text-white">{{ temperatura.valor }}°C</p>
                            <p class="text-xs text-gray-500 mt-1">{{ temperatura.lugar }}</p>
                        </div>
                        <div v-else class="space-y-2">
                            <p class="text-3xl font-bold text-gray-700">—°C</p>
                            <p class="text-xs text-gray-700">Sin sensor conectado</p>
                            <span class="inline-block text-[10px] font-medium bg-gray-800 text-gray-600 px-2 py-0.5 rounded-full mt-1">
                                Próximamente
                            </span>
                        </div>
                        <svg class="absolute right-4 bottom-4 w-10 h-10 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                  d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>

                    <!-- Estado de la casa (computed real) -->
                    <div class="bg-[#111827] border rounded-xl p-5 relative overflow-hidden"
                         :class="estadoCasa.seguro ? 'border-gray-800' : 'border-red-900/50'">
                        <p class="text-xs font-semibold uppercase tracking-wider mb-3"
                           :class="estadoCasa.seguro ? 'text-yellow-400' : 'text-red-400'">
                            Estado de la casa
                        </p>
                        <p class="text-xl font-bold"
                           :class="estadoCasa.seguro ? 'text-white' : 'text-red-400'">
                            {{ estadoCasa.texto }}
                        </p>
                        <p class="text-xs mt-1"
                           :class="estadoCasa.seguro ? 'text-gray-500' : 'text-red-600'">
                            {{ estadoCasa.detalle }}
                        </p>
                        <svg class="absolute right-4 bottom-4 w-10 h-10"
                             :class="estadoCasa.seguro ? 'text-gray-800' : 'text-red-900'"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                  d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>

                </div>

                <!-- Main panels -->
                <div class="grid grid-cols-3 gap-4 mb-4">

                    <!-- LUCES -->
                    <div class="bg-[#111827] border border-gray-800 rounded-xl p-5 flex flex-col">
                        <div class="flex items-center gap-2 mb-4">
                            <svg class="w-5 h-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                            </svg>
                            <h3 class="text-sm font-bold text-white uppercase tracking-wide">Luces</h3>
                        </div>

                        <div v-if="luces.length === 0" class="flex-1 flex items-center justify-center">
                            <p class="text-xs text-gray-600">Sin luces registradas</p>
                        </div>
                        <ul v-else class="space-y-3 flex-1">
                            <li v-for="d in luces" :key="d.id"
                                class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-white">{{ d.nombre }}</p>
                                    <p class="text-xs" :class="activo(d) ? 'text-green-400' : 'text-gray-600'">
                                        {{ capitalizar(d.estado) }}
                                    </p>
                                </div>
                                <button @click="toggle(d)" :disabled="!!enviando[d.id]"
                                        class="relative inline-flex h-5 w-9 rounded-full transition-colors focus:outline-none disabled:opacity-50"
                                        :class="activo(d) ? 'bg-blue-600' : 'bg-gray-700'">
                                    <span class="inline-block w-3.5 h-3.5 transform rounded-full bg-white transition-transform mt-[3px]"
                                          :class="activo(d) ? 'translate-x-4' : 'translate-x-1'"></span>
                                </button>
                            </li>
                        </ul>

                        <a :href="`/direcciones/${direccionId}`"
                           class="mt-4 w-full text-center text-xs font-semibold text-blue-400 hover:text-blue-300 border border-blue-900/50 hover:border-blue-700 rounded-lg py-2 transition-colors">
                            Ver todas las luces
                        </a>
                    </div>

                    <!-- PERSIANAS -->
                    <div class="bg-[#111827] border border-gray-800 rounded-xl p-5 flex flex-col">
                        <div class="flex items-center gap-2 mb-4">
                            <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                            </svg>
                            <h3 class="text-sm font-bold text-white uppercase tracking-wide">Persianas</h3>
                        </div>

                        <div v-if="persianas.length === 0" class="flex-1 flex items-center justify-center">
                            <p class="text-xs text-gray-600">Sin persianas registradas</p>
                        </div>
                        <ul v-else class="space-y-3 flex-1">
                            <li v-for="d in persianas" :key="d.id">
                                <div class="flex items-center justify-between mb-1.5">
                                    <p class="text-sm font-medium text-white">{{ d.nombre }}</p>
                                    <span class="text-xs" :class="activo(d) ? 'text-green-400' : 'text-gray-600'">
                                        {{ capitalizar(d.estado) }}
                                    </span>
                                </div>
                                <div class="flex gap-2">
                                    <button @click="enviarComando(d, 'abrir')"
                                            :disabled="d.estado === 'abierto' || !!enviando[d.id]"
                                            class="flex-1 text-xs font-medium py-1.5 rounded-lg border transition-colors disabled:opacity-30 disabled:cursor-not-allowed
                                                   border-green-800/60 text-green-400 hover:bg-green-900/30">
                                        Abrir
                                    </button>
                                    <button @click="enviarComando(d, 'cerrar')"
                                            :disabled="d.estado === 'cerrado' || !!enviando[d.id]"
                                            class="flex-1 text-xs font-medium py-1.5 rounded-lg border transition-colors disabled:opacity-30 disabled:cursor-not-allowed
                                                   border-gray-700 text-gray-400 hover:bg-gray-800">
                                        Cerrar
                                    </button>
                                </div>
                            </li>
                        </ul>

                        <div class="mt-4 w-full text-center text-xs font-semibold text-gray-600 border border-gray-800 rounded-lg py-2 cursor-not-allowed select-none">
                            Modo automático
                            <span class="text-[10px] text-gray-700 ml-1">(próximamente)</span>
                        </div>
                    </div>

                    <!-- SEGURIDAD -->
                    <div class="bg-[#111827] border border-gray-800 rounded-xl p-5 flex flex-col">
                        <div class="flex items-center gap-2 mb-4">
                            <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                            <h3 class="text-sm font-bold text-white uppercase tracking-wide">Seguridad</h3>
                        </div>

                        <div v-if="seguridad.length === 0" class="flex-1 flex items-center justify-center">
                            <p class="text-xs text-gray-600">Sin sensores registrados</p>
                        </div>
                        <ul v-else class="space-y-3 flex-1">
                            <li v-for="d in seguridad" :key="d.id"
                                class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-white">{{ d.nombre }}</p>
                                    <p class="text-xs text-gray-500">{{ labels[d.tipo] }}</p>
                                </div>
                                <span class="inline-flex items-center gap-1.5 text-xs font-medium px-2.5 py-1 rounded-full"
                                      :class="activo(d)
                                          ? 'bg-red-900/40 text-red-400 border border-red-800/40'
                                          : 'bg-gray-800 text-gray-500 border border-gray-700'">
                                    <span class="w-1.5 h-1.5 rounded-full"
                                          :class="activo(d) ? 'bg-red-400' : 'bg-gray-600'"></span>
                                    {{ capitalizar(d.estado) }}
                                </span>
                            </li>
                        </ul>

                        <div class="mt-4 w-full text-center text-xs font-semibold text-gray-600 border border-gray-800 rounded-lg py-2 cursor-not-allowed select-none">
                            Ver cámaras
                            <span class="text-[10px] text-gray-700 ml-1">(próximamente)</span>
                        </div>
                    </div>

                </div>

                <!-- Escenas rápidas -->
                <div class="bg-[#111827] border border-gray-800 rounded-xl p-5">
                    <div class="flex items-center gap-2 mb-4">
                        <svg class="w-5 h-5 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                        </svg>
                        <h3 class="text-sm font-bold text-white uppercase tracking-wide">Escenas rápidas</h3>
                        <span class="text-[10px] font-medium bg-gray-800 text-gray-600 px-2 py-0.5 rounded-full ml-1">Próximamente</span>
                    </div>
                    <div class="grid grid-cols-4 gap-3">
                        <button v-for="escena in escenas" :key="escena.label" disabled
                                class="flex items-center justify-between bg-gray-800/60 border border-gray-700/50 rounded-xl px-4 py-3.5 text-left opacity-50 cursor-not-allowed">
                            <div>
                                <p class="text-sm font-semibold text-white">{{ escena.label }}</p>
                                <p class="text-xs text-gray-500 mt-0.5">Activar</p>
                            </div>
                            <svg class="w-4 h-4 text-gray-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </button>
                    </div>
                </div>

            </template>
        </template>

    </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import axios from 'axios'

const props = defineProps({
    clientes: { type: Array, default: () => [] },
})

const clienteId   = ref('')
const direccionId = ref('')
const dispositivos = ref([])
const cargando    = ref(false)
const enviando    = ref({})

// Placeholders — listos para conectar a sus APIs
const clima       = ref(null)   // GET /api/clima/{direccion_id}
const consumo     = ref(null)   // GET /api/consumo/{direccion_id}
const temperatura = ref(null)   // del sensor de calefaccion

const escenas = [
    { label: 'Modo Noche' },
    { label: 'Modo Fiesta' },
    { label: 'Salir de casa' },
    { label: 'Llegar a casa' },
]

const labels = {
    luz:            'Luz',
    persiana:       'Persiana',
    sensor_puerta:  'Sensor de puerta',
    sensor_ventana: 'Sensor de ventana',
    camara:         'Cámara',
    calefaccion:    'Calefacción',
}

const estadosActivos = ['encendido', 'abierto', 'activo']

const accionPara = {
    encendido: 'apagar',
    apagado:   'encender',
    abierto:   'cerrar',
    cerrado:   'abrir',
    activo:    'desactivar',
    inactivo:  'activar',
}

const estadoNuevoPara = {
    encender:   'encendido',
    apagar:     'apagado',
    abrir:      'abierto',
    cerrar:     'cerrado',
    activar:    'activo',
    desactivar: 'inactivo',
}

// Computed
const direccionesDelCliente = computed(() => {
    if (!clienteId.value) return []
    return props.clientes.find(c => c.id == clienteId.value)?.direcciones ?? []
})

const luces     = computed(() => dispositivos.value.filter(d => d.tipo === 'luz'))
const persianas = computed(() => dispositivos.value.filter(d => d.tipo === 'persiana'))
const seguridad = computed(() => dispositivos.value.filter(d =>
    ['sensor_puerta', 'sensor_ventana', 'camara'].includes(d.tipo)
))

const estadoCasa = computed(() => {
    const sensores = dispositivos.value.filter(d =>
        ['sensor_puerta', 'sensor_ventana'].includes(d.tipo)
    )
    if (sensores.length === 0)
        return { texto: 'Sin sensores', detalle: 'No hay sensores configurados', seguro: true }
    const alertas = sensores.filter(d => d.estado === 'abierto' || d.estado === 'activo')
    if (alertas.length === 0)
        return { texto: 'Todo seguro', detalle: 'Sin alertas activas', seguro: true }
    return {
        texto:   `${alertas.length} alerta${alertas.length > 1 ? 's' : ''}`,
        detalle: alertas.map(d => d.nombre).join(', '),
        seguro:  false,
    }
})

// Helpers
function activo(d)      { return estadosActivos.includes(d.estado) }
function capitalizar(s) { return s ? s.charAt(0).toUpperCase() + s.slice(1) : '' }

// Handlers
function onClienteChange() {
    direccionId.value  = ''
    dispositivos.value = []
    clima.value        = null
    consumo.value      = null
    temperatura.value  = null
}

async function onDireccionChange() {
    if (!direccionId.value) return
    cargando.value     = true
    dispositivos.value = []
    clima.value        = null
    consumo.value      = null
    temperatura.value  = null
    try {
        const { data } = await axios.get(`/api/dispositivos/${direccionId.value}`)
        dispositivos.value = data.data
    } finally {
        cargando.value = false
    }
}

async function enviarComando(d, accion) {
    const estadoAntes = d.estado
    d.estado = estadoNuevoPara[accion]
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

function toggle(d) {
    enviarComando(d, accionPara[d.estado])
}
</script>
