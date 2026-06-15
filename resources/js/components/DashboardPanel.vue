<template>
    <div>

        <!-- Selector bar -->
        <div class="flex items-center gap-3 mb-6">
            <div class="relative">
                <select v-model="clientId" @change="onClientChange"
                        class="appearance-none bg-[#111827] border border-gray-700 text-sm text-white rounded-lg px-4 py-2.5 pr-9 focus:outline-none focus:border-blue-500 cursor-pointer min-w-44">
                    <option value="">Select client</option>
                    <option v-for="c in clients" :key="c.id" :value="c.id">{{ c.name }}</option>
                </select>
                <svg class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-500"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </div>

            <div class="relative">
                <select v-model="addressId" @change="onAddressChange"
                        :disabled="!clientId || clientAddresses.length === 0"
                        class="appearance-none bg-[#111827] border border-gray-700 text-sm text-white rounded-lg px-4 py-2.5 pr-9 focus:outline-none focus:border-blue-500 cursor-pointer min-w-52 disabled:opacity-40 disabled:cursor-not-allowed">
                    <option value="">Select property</option>
                    <option v-for="a in clientAddresses" :key="a.id" :value="a.id">{{ a.alias }}</option>
                </select>
                <svg class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-500"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </div>
        </div>

        <!-- Empty state -->
        <div v-if="!addressId" class="flex flex-col items-center justify-center py-24 text-center">
            <div class="w-14 h-14 bg-[#111827] border border-gray-800 rounded-2xl flex items-center justify-center mb-4">
                <svg class="w-7 h-7 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
            </div>
            <p class="text-sm text-gray-500">Select a client and a property to view the panel.</p>
        </div>

        <template v-else>
            <div v-if="loading" class="flex items-center justify-center py-24">
                <div class="w-6 h-6 border-2 border-gray-700 border-t-blue-500 rounded-full animate-spin"></div>
            </div>

            <template v-else>

                <!-- Stats row -->
                <div class="grid grid-cols-4 gap-4 mb-6">

                    <!-- Climate (placeholder) -->
                    <div class="bg-[#111827] border border-gray-800 rounded-xl p-5 relative overflow-hidden">
                        <p class="text-xs font-semibold text-blue-400 uppercase tracking-wider mb-3">Outside climate</p>
                        <div v-if="climate">
                            <p v-if="climate.city" class="text-xs text-gray-500 mb-1">{{ climate.city }}</p>
                            <p class="text-3xl font-bold text-white">{{ climate.temp }}°C</p>
                            <p class="text-sm text-gray-400">{{ climate.description }}</p>
                            <p class="text-xs text-gray-600 mt-2">Humidity: {{ climate.humidity }}% · Wind: {{ climate.wind }} km/h</p>
                        </div>
                        <div v-else class="space-y-2">
                            <p class="text-3xl font-bold text-gray-700">—°C</p>
                            <p class="text-xs text-gray-700">Humidity: — · Wind: —</p>
                        </div>
                        <svg class="absolute right-4 bottom-4 w-10 h-10 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"/>
                        </svg>
                    </div>

                    <!-- Power consumption (placeholder) -->
                    <div class="bg-[#111827] border border-gray-800 rounded-xl p-5 relative overflow-hidden">
                        <p class="text-xs font-semibold text-green-400 uppercase tracking-wider mb-3">Power usage</p>
                        <div v-if="consumption">
                            <p class="text-3xl font-bold text-white">{{ consumption.current }} kW</p>
                            <p class="text-xs text-gray-500 mt-1">Today: {{ consumption.today }} kWh</p>
                        </div>
                        <div v-else class="space-y-2">
                            <p class="text-3xl font-bold text-gray-700">— kW</p>
                            <p class="text-xs text-gray-700">Today: — kWh</p>
                            <span class="inline-block text-[10px] font-medium bg-gray-800 text-gray-600 px-2 py-0.5 rounded-full mt-1">Coming soon</span>
                        </div>
                        <svg class="absolute right-4 bottom-4 w-10 h-10 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>

                    <!-- Indoor temperature -->
                    <div class="bg-[#111827] border border-gray-800 rounded-xl p-5 relative overflow-hidden">
                        <div class="flex items-center justify-between mb-3">
                            <p class="text-xs font-semibold text-orange-400 uppercase tracking-wider">Indoor temperature</p>
                            <button v-if="temperature" @click="openHeatingModal"
                                    class="text-[10px] font-medium text-gray-500 hover:text-orange-400 border border-gray-700 hover:border-orange-800 rounded-md px-2 py-0.5 transition-colors">
                                Calibrate
                            </button>
                        </div>
                        <div v-if="temperature">
                            <p class="text-3xl font-bold text-white">{{ temperature.value }}°F</p>
                            <p class="text-xs text-gray-500 mt-1">
                                Humidity: {{ temperature.humidity }}% ·
                                <span :class="temperature.status === 'on' ? 'text-orange-400' : 'text-gray-500'">
                                    Heating {{ temperature.status }}
                                </span>
                            </p>
                            <p class="text-xs text-gray-600 mt-1">{{ temperature.device }}</p>
                        </div>
                        <div v-else class="space-y-2">
                            <p class="text-3xl font-bold text-gray-700">0°F</p>
                            <p class="text-xs text-gray-700">No sensor connected</p>
                        </div>
                        <svg class="absolute right-4 bottom-4 w-10 h-10 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>

                    <!-- Home status (computed) -->
                    <div class="bg-[#111827] border rounded-xl p-5 relative overflow-hidden"
                         :class="homeStatus.safe ? 'border-gray-800' : 'border-red-900/50'">
                        <p class="text-xs font-semibold uppercase tracking-wider mb-3"
                           :class="homeStatus.safe ? 'text-yellow-400' : 'text-red-400'">Home status</p>
                        <p class="text-xl font-bold" :class="homeStatus.safe ? 'text-white' : 'text-red-400'">
                            {{ homeStatus.text }}
                        </p>
                        <p class="text-xs mt-1" :class="homeStatus.safe ? 'text-gray-500' : 'text-red-600'">
                            {{ homeStatus.detail }}
                        </p>
                        <svg class="absolute right-4 bottom-4 w-10 h-10"
                             :class="homeStatus.safe ? 'text-gray-800' : 'text-red-900'"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                  d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>

                </div>

                <!-- Main panels -->
                <div class="grid grid-cols-3 gap-4 mb-4">

                    <!-- LIGHTS -->
                    <div class="bg-[#111827] border border-gray-800 rounded-xl p-5 flex flex-col">
                        <div class="flex items-center gap-2 mb-4">
                            <svg class="w-5 h-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                            </svg>
                            <h3 class="text-sm font-bold text-white uppercase tracking-wide">Lights</h3>
                        </div>
                        <div v-if="lights.length === 0" class="flex-1 flex items-center justify-center">
                            <p class="text-xs text-gray-600">No lights registered</p>
                        </div>
                        <ul v-else class="space-y-3 flex-1">
                            <li v-for="d in lights" :key="d.id" class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-white">{{ d.name }}</p>
                                    <p class="text-xs" :class="isActive(d) ? 'text-green-400' : 'text-gray-600'">
                                        {{ capitalize(d.status) }}
                                    </p>
                                </div>
                                <button @click="toggle(d)" :disabled="!!sending[d.id]"
                                        class="relative inline-flex h-5 w-9 rounded-full transition-colors focus:outline-none disabled:opacity-50"
                                        :class="isActive(d) ? 'bg-blue-600' : 'bg-gray-700'">
                                    <span class="inline-block w-3.5 h-3.5 transform rounded-full bg-white transition-transform mt-[3px]"
                                          :class="isActive(d) ? 'translate-x-4' : 'translate-x-1'"></span>
                                </button>
                            </li>
                        </ul>
                        <a :href="`/addresses/${addressId}`"
                           class="mt-4 w-full text-center text-xs font-semibold text-blue-400 hover:text-blue-300 border border-blue-900/50 hover:border-blue-700 rounded-lg py-2 transition-colors">
                            View all devices
                        </a>
                    </div>

                    <!-- BLINDS -->
                    <div class="bg-[#111827] border border-gray-800 rounded-xl p-5 flex flex-col">
                        <div class="flex items-center gap-2 mb-4">
                            <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                            </svg>
                            <h3 class="text-sm font-bold text-white uppercase tracking-wide">Blinds</h3>
                        </div>
                        <div v-if="blinds.length === 0" class="flex-1 flex items-center justify-center">
                            <p class="text-xs text-gray-600">No blinds registered</p>
                        </div>
                        <ul v-else class="space-y-3 flex-1">
                            <li v-for="d in blinds" :key="d.id">
                                <div class="flex items-center justify-between mb-1.5">
                                    <p class="text-sm font-medium text-white">{{ d.name }}</p>
                                    <span class="text-xs" :class="isActive(d) ? 'text-green-400' : 'text-gray-600'">
                                        {{ capitalize(d.status) }}
                                    </span>
                                </div>
                                <div class="flex gap-2">
                                    <button @click="sendCommand(d, 'open')"
                                            :disabled="d.status === 'open' || !!sending[d.id]"
                                            class="flex-1 text-xs font-medium py-1.5 rounded-lg border transition-colors disabled:opacity-30 disabled:cursor-not-allowed border-green-800/60 text-green-400 hover:bg-green-900/30">
                                        Open
                                    </button>
                                    <button @click="sendCommand(d, 'close')"
                                            :disabled="d.status === 'closed' || !!sending[d.id]"
                                            class="flex-1 text-xs font-medium py-1.5 rounded-lg border transition-colors disabled:opacity-30 disabled:cursor-not-allowed border-gray-700 text-gray-400 hover:bg-gray-800">
                                        Close
                                    </button>
                                </div>
                            </li>
                        </ul>
                        <div class="mt-4 w-full text-center text-xs font-semibold text-gray-600 border border-gray-800 rounded-lg py-2 cursor-not-allowed select-none">
                            Auto mode <span class="text-[10px] text-gray-700 ml-1">(coming soon)</span>
                        </div>
                    </div>

                    <!-- SECURITY -->
                    <div class="bg-[#111827] border border-gray-800 rounded-xl p-5 flex flex-col">
                        <div class="flex items-center gap-2 mb-4">
                            <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                            <h3 class="text-sm font-bold text-white uppercase tracking-wide">Security</h3>
                        </div>
                        <div v-if="security.length === 0" class="flex-1 flex items-center justify-center">
                            <p class="text-xs text-gray-600">No sensors registered</p>
                        </div>
                        <ul v-else class="space-y-3 flex-1">
                            <li v-for="d in security" :key="d.id" class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-white">{{ d.name }}</p>
                                    <p class="text-xs text-gray-500">{{ labels[d.type] }}</p>
                                </div>
                                <span class="inline-flex items-center gap-1.5 text-xs font-medium px-2.5 py-1 rounded-full"
                                      :class="isActive(d)
                                          ? 'bg-red-900/40 text-red-400 border border-red-800/40'
                                          : 'bg-gray-800 text-gray-500 border border-gray-700'">
                                    <span class="w-1.5 h-1.5 rounded-full" :class="isActive(d) ? 'bg-red-400' : 'bg-gray-600'"></span>
                                    {{ capitalize(d.status) }}
                                </span>
                            </li>
                        </ul>
                        <div class="mt-4 w-full text-center text-xs font-semibold text-gray-600 border border-gray-800 rounded-lg py-2 cursor-not-allowed select-none">
                            View cameras <span class="text-[10px] text-gray-700 ml-1">(coming soon)</span>
                        </div>
                    </div>

                </div>

                <!-- Scenes -->
                <div class="bg-[#111827] border border-gray-800 rounded-xl p-5">
                    <div class="flex items-center gap-2 mb-4">
                        <svg class="w-5 h-5 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                        </svg>
                        <h3 class="text-sm font-bold text-white uppercase tracking-wide">Quick scenes</h3>
                        <span class="text-[10px] font-medium bg-gray-800 text-gray-600 px-2 py-0.5 rounded-full ml-1">Coming soon</span>
                    </div>
                    <div class="grid grid-cols-4 gap-3">
                        <button v-for="scene in scenes" :key="scene" disabled
                                class="flex items-center justify-between bg-gray-800/60 border border-gray-700/50 rounded-xl px-4 py-3.5 text-left opacity-50 cursor-not-allowed">
                            <div>
                                <p class="text-sm font-semibold text-white">{{ scene }}</p>
                                <p class="text-xs text-gray-500 mt-0.5">Activate</p>
                            </div>
                            <svg class="w-4 h-4 text-gray-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </button>
                    </div>
                </div>

            </template>
        </template>

    <!-- Heating calibration modal -->
    <Teleport to="body">
        <div v-if="heatingModal" class="fixed inset-0 z-50 flex items-center justify-center">
            <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="heatingModal = false"></div>
            <div class="relative bg-[#111827] border border-gray-700 rounded-2xl p-6 w-full max-w-sm shadow-xl">

                <div class="flex items-center justify-between mb-5">
                    <h2 class="text-sm font-bold text-white uppercase tracking-wide">Calibrate heating</h2>
                    <button @click="heatingModal = false" class="text-gray-600 hover:text-gray-400 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <div class="space-y-4">

                    <!-- Target temperature -->
                    <div>
                        <label class="block text-xs font-medium text-gray-400 mb-1.5">Target temperature (°F)</label>
                        <input v-model.number="heatingForm.target_temperature"
                               type="number" min="32" max="122" step="1"
                               class="w-full bg-[#0d1117] border border-gray-700 text-white text-sm rounded-lg px-3 py-2.5 focus:outline-none focus:border-orange-500"/>
                    </div>

                    <!-- Heating mode -->
                    <div>
                        <label class="block text-xs font-medium text-gray-400 mb-1.5">Mode</label>
                        <div class="grid grid-cols-2 gap-2">
                            <button @click="heatingForm.heating_mode = 'manual'"
                                    class="py-2 text-xs font-semibold rounded-lg border transition-colors"
                                    :class="heatingForm.heating_mode === 'manual'
                                        ? 'bg-orange-900/30 border-orange-700 text-orange-400'
                                        : 'bg-transparent border-gray-700 text-gray-500 hover:border-gray-600'">
                                Manual
                            </button>
                            <button @click="heatingForm.heating_mode = 'auto'"
                                    class="py-2 text-xs font-semibold rounded-lg border transition-colors"
                                    :class="heatingForm.heating_mode === 'auto'
                                        ? 'bg-orange-900/30 border-orange-700 text-orange-400'
                                        : 'bg-transparent border-gray-700 text-gray-500 hover:border-gray-600'">
                                Auto
                            </button>
                        </div>
                    </div>

                    <!-- Heating status -->
                    <div>
                        <label class="block text-xs font-medium text-gray-400 mb-1.5">Heating</label>
                        <div class="grid grid-cols-2 gap-2">
                            <button @click="heatingForm.heating_status = 'on'"
                                    class="py-2 text-xs font-semibold rounded-lg border transition-colors"
                                    :class="heatingForm.heating_status === 'on'
                                        ? 'bg-orange-900/30 border-orange-700 text-orange-400'
                                        : 'bg-transparent border-gray-700 text-gray-500 hover:border-gray-600'">
                                On
                            </button>
                            <button @click="heatingForm.heating_status = 'off'"
                                    class="py-2 text-xs font-semibold rounded-lg border transition-colors"
                                    :class="heatingForm.heating_status === 'off'
                                        ? 'bg-gray-800 border-gray-600 text-gray-300'
                                        : 'bg-transparent border-gray-700 text-gray-500 hover:border-gray-600'">
                                Off
                            </button>
                        </div>
                    </div>

                </div>

                <div class="flex gap-2 mt-6">
                    <button @click="heatingModal = false"
                            class="flex-1 py-2.5 text-xs font-semibold text-gray-400 border border-gray-700 rounded-lg hover:border-gray-600 transition-colors">
                        Cancel
                    </button>
                    <button @click="saveHeating" :disabled="heatingLoading"
                            class="flex-1 py-2.5 text-xs font-semibold text-white bg-orange-600 hover:bg-orange-500 disabled:opacity-50 rounded-lg transition-colors">
                        {{ heatingLoading ? 'Saving…' : 'Save' }}
                    </button>
                </div>

            </div>
        </div>
    </Teleport>

    </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import axios from 'axios'

const props = defineProps({
    clients: { type: Array, default: () => [] },
})

const clientId  = ref('')
const addressId = ref('')
const devices   = ref([])
const loading   = ref(false)
const sending   = ref({})

const climate     = ref(null)
const consumption = ref(null)
const temperature = ref(null)

const heatingModal   = ref(false)
const heatingForm    = ref({ target_temperature: 0, heating_mode: 'manual', heating_status: 'off' })
const heatingLoading = ref(false)

function openHeatingModal() {
    heatingForm.value = {
        target_temperature: temperature.value?.target ?? 68,
        heating_mode:       temperature.value?.mode   ?? 'manual',
        heating_status:     temperature.value?.status ?? 'off',
    }
    heatingModal.value = true
}

async function saveHeating() {
    heatingLoading.value = true
    try {
        await axios.patch(`/api/heating/${addressId.value}/calibrate`, heatingForm.value)
        await fetchHeating(addressId.value)
        heatingModal.value = false
    } catch (e) {
        alert('Could not save heating settings. Please try again.')
    } finally {
        heatingLoading.value = false
    }
}

const scenes = ['Night mode', 'Party mode', 'Leaving home', 'Arriving home']

const labels = {
    light:         'Light',
    blind:         'Blind',
    door_sensor:   'Door sensor',
    window_sensor: 'Window sensor',
    camera:        'Camera',
    heating:       'Heating',
}

const activeStatuses = ['on', 'open', 'active']

const actionFor = {
    on:       'turn_off',
    off:      'turn_on',
    open:     'close',
    closed:   'open',
    active:   'deactivate',
    inactive: 'activate',
}

const statusFor = {
    turn_on:    'on',
    turn_off:   'off',
    open:       'open',
    close:      'closed',
    activate:   'active',
    deactivate: 'inactive',
}

const clientAddresses = computed(() => {
    if (!clientId.value) return []
    return props.clients.find(c => c.id == clientId.value)?.addresses ?? []
})

const lights   = computed(() => devices.value.filter(d => d.type === 'light'))
const blinds   = computed(() => devices.value.filter(d => d.type === 'blind'))
const security = computed(() => devices.value.filter(d => ['door_sensor', 'window_sensor', 'camera'].includes(d.type)))

const homeStatus = computed(() => {
    const sensors = devices.value.filter(d => ['door_sensor', 'window_sensor'].includes(d.type))
    if (sensors.length === 0)
        return { text: 'No sensors', detail: 'No sensors configured', safe: true }
    const alerts = sensors.filter(d => d.status === 'open' || d.status === 'active')
    if (alerts.length === 0)
        return { text: 'All secure', detail: 'No active alerts', safe: true }
    return {
        text:   `${alerts.length} alert${alerts.length > 1 ? 's' : ''}`,
        detail: alerts.map(d => d.name).join(', '),
        safe:   false,
    }
})

function isActive(d)   { return activeStatuses.includes(d.status) }
function capitalize(s) { return s ? s.charAt(0).toUpperCase() + s.slice(1) : '' }

function onClientChange() {
    addressId.value  = ''
    devices.value    = []
    climate.value    = null
    consumption.value = null
    temperature.value = null
}

function weatherCodeToDescription(code) {
    if (code === 0)              return 'Clear sky'
    if (code <= 2)               return 'Partly cloudy'
    if (code === 3)              return 'Overcast'
    if (code <= 49)              return 'Foggy'
    if (code <= 59)              return 'Drizzle'
    if (code <= 69)              return 'Rain'
    if (code <= 79)              return 'Snow'
    if (code <= 82)              return 'Rain showers'
    if (code <= 86)              return 'Snow showers'
    if (code <= 99)              return 'Thunderstorm'
    return 'Unknown'
}

async function fetchHeating(addressId) {
    try {
        const { data } = await axios.get(`/api/heating/${addressId}`)
        if (!data) return
        temperature.value = {
            value:    data.target_temperature ?? 0,
            humidity: data.humidity ?? 0,
            status:   data.heating_status,
            mode:     data.heating_mode,
            target:   data.target_temperature ?? 0,
            device:   data.device,
        }
    } catch {
        temperature.value = null
    }
}

async function fetchCityName(lat, lon) {
    try {
        const { data } = await axios.get('https://nominatim.openstreetmap.org/reverse', {
            params: { lat, lon, format: 'json' },
            headers: { 'Accept-Language': 'en' },
        })
        return data.address?.city
            || data.address?.town
            || data.address?.village
            || data.address?.county
            || null
    } catch {
        return null
    }
}

async function fetchClimate(lat, lon) {
    if (!lat || !lon) return
    try {
        const [weatherRes, city] = await Promise.all([
            axios.get('https://api.open-meteo.com/v1/forecast', {
                params: {
                    latitude: lat,
                    longitude: lon,
                    current: 'temperature_2m,relative_humidity_2m,wind_speed_10m,weather_code',
                },
            }),
            fetchCityName(lat, lon),
        ])
        const c = weatherRes.data.current
        climate.value = {
            temp:        Math.round(c.temperature_2m),
            humidity:    c.relative_humidity_2m,
            wind:        Math.round(c.wind_speed_10m),
            description: weatherCodeToDescription(c.weather_code),
            city,
        }
    } catch {
        climate.value = null
    }
}

async function onAddressChange() {
    if (!addressId.value) return
    loading.value  = true
    devices.value  = []
    climate.value  = null
    consumption.value = null
    temperature.value = null
    const address = clientAddresses.value.find(a => a.id == addressId.value)
    try {
        const [devicesRes, heatingRes] = await Promise.all([
            axios.get(`/api/devices/${addressId.value}`),
            fetchClimate(address?.latitude, address?.longitude),
            fetchHeating(addressId.value),
        ])
        devices.value = devicesRes.data.data
    } finally {
        loading.value = false
    }
}

async function sendCommand(d, action) {
    const prevStatus = d.status
    d.status = statusFor[action]
    sending.value[d.id] = true
    try {
        const { data } = await axios.post('/api/commands', { device_id: d.id, action })
        d.status = data.device.status
    } catch {
        d.status = prevStatus
    } finally {
        delete sending.value[d.id]
    }
}

function toggle(d) {
    sendCommand(d, actionFor[d.status])
}
</script>
