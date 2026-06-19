<template>
    <div>
        <div v-if="loading" class="bg-[#111827] border border-gray-800 rounded-xl p-12 text-center">
            <div class="w-6 h-6 border-2 border-gray-700 border-t-blue-500 rounded-full animate-spin mx-auto mb-3"></div>
            <p class="text-sm text-gray-500">Loading devices...</p>
        </div>

        <div v-else-if="error" class="bg-[#111827] border border-red-900/40 rounded-xl p-6 text-center">
            <p class="text-sm text-red-400">{{ error }}</p>
            <button @click="load" class="mt-3 text-xs text-gray-400 hover:text-white transition-colors underline">Retry</button>
        </div>

        <div v-else-if="devices.length === 0" class="bg-[#111827] border border-gray-800 rounded-xl p-12 text-center">
            <svg class="w-10 h-10 mx-auto mb-3 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
            </svg>
            <p class="text-sm text-gray-600">No devices registered in this property.</p>
        </div>

        <div v-else class="bg-[#111827] border border-gray-800 rounded-xl overflow-hidden">
            <!-- Toolbar -->
            <div class="flex gap-3 p-3 border-b border-gray-800">
                <div class="relative flex-1">
                    <svg class="absolute left-2.5 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-gray-500 pointer-events-none"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M21 21l-4.35-4.35M17 11A6 6 0 115 11a6 6 0 0112 0z"/>
                    </svg>
                    <input v-model="search" type="text" placeholder="Search by name…"
                           class="w-full bg-gray-800 border border-gray-700 rounded-lg pl-8 pr-3 py-1.5
                                  text-sm text-white placeholder-gray-500 focus:outline-none focus:border-blue-600"/>
                </div>
                <select v-model="typeFilter"
                        class="bg-gray-800 border border-gray-700 rounded-lg px-3 py-1.5
                               text-sm text-gray-300 focus:outline-none focus:border-blue-600">
                    <option value="">All types</option>
                    <option v-for="(label, key) in labels" :key="key" :value="key">{{ label }}</option>
                </select>
            </div>

            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-800">
                        <th class="px-3 py-3 text-left">
                            <button @click="setSort('id')"
                                    class="flex items-center gap-1 text-xs font-medium text-gray-500 uppercase hover:text-gray-300 transition-colors">
                                ID <SortIcon col="id" :sortKey="sortKey" :sortDir="sortDir"/>
                            </button>
                        </th>
                        <th class="px-6 py-3 text-left">
                            <button @click="setSort('name')"
                                    class="flex items-center gap-1 text-xs font-medium text-gray-500 uppercase tracking-wider hover:text-gray-300 transition-colors">
                                Device <SortIcon col="name" :sortKey="sortKey" :sortDir="sortDir"/>
                            </button>
                        </th>
                        <th class="px-6 py-3 text-left">
                            <button @click="setSort('type')"
                                    class="flex items-center gap-1 text-xs font-medium text-gray-500 uppercase tracking-wider hover:text-gray-300 transition-colors">
                                Type <SortIcon col="type" :sortKey="sortKey" :sortDir="sortDir"/>
                            </button>
                        </th>
                        <th class="px-6 py-3 text-left">
                            <button @click="setSort('status')"
                                    class="flex items-center gap-1 text-xs font-medium text-gray-500 uppercase tracking-wider hover:text-gray-300 transition-colors">
                                Status <SortIcon col="status" :sortKey="sortKey" :sortDir="sortDir"/>
                            </button>
                        </th>
                        <th class="px-6 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-800">
                    <tr v-if="paginated.length === 0">
                        <td colspan="5" class="px-6 py-8 text-center text-sm text-gray-600">No devices match the current filter.</td>
                    </tr>
                    <tr v-for="d in paginated" :key="d.id" class="hover:bg-gray-800/40 transition-colors">
                        <td class="px-3 py-4 text-gray-400">{{ d.id }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-gray-800 border border-gray-700 flex items-center justify-center shrink-0"
                                     :class="isActive(d) ? 'border-blue-800/60 bg-blue-900/20' : ''">
                                    <svg class="w-4 h-4" :class="isActive(d) ? 'text-blue-400' : 'text-gray-400'"
                                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" :d="icons[d.type] ?? icons.light"/>
                                    </svg>
                                </div>
                                <span class="font-medium text-white">{{ d.name }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-gray-400">{{ labels[d.type] ?? d.type }}</td>
                        <td class="px-6 py-4">
                            <div class="flex flex-col gap-1">
                                <span class="inline-flex items-center gap-1.5 text-xs font-medium px-2.5 py-1 rounded-full"
                                      :class="isActive(d)
                                          ? 'bg-green-900/40 text-green-400 border border-green-800/40'
                                          : 'bg-gray-800 text-gray-500 border border-gray-700'">
                                    <span class="w-1.5 h-1.5 rounded-full" :class="isActive(d) ? 'bg-green-400' : 'bg-gray-600'"></span>
                                    {{ capitalize(d.status) }}
                                </span>
                                <span v-if="d.type === 'heating' && d.settings?.target_temperature !== undefined"
                                      class="text-xs text-gray-500 px-2.5">
                                    Target: {{ d.settings.target_temperature }}°F
                                </span>
                                <span v-if="d.type === 'blind' && d.settings?.position !== undefined"
                                      class="text-xs text-gray-500 px-2.5">
                                    Position: {{ d.settings.position }}%
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-end gap-4">
                                <template v-if="d.type === 'heating'">
                                    <div class="flex items-center gap-1">
                                        <button @click="adjustTemperature(d, -1)"
                                                :disabled="!!sending[d.id]"
                                                class="w-6 h-6 rounded bg-gray-800 border border-gray-700 text-gray-400 hover:text-white text-sm leading-none transition-colors disabled:opacity-40">−</button>
                                        <span class="w-12 text-center text-xs text-gray-300">
                                            {{ d.settings?.target_temperature !== undefined ? d.settings.target_temperature + '°F' : '--' }}
                                        </span>
                                        <button @click="adjustTemperature(d, 1)"
                                                :disabled="!!sending[d.id]"
                                                class="w-6 h-6 rounded bg-gray-800 border border-gray-700 text-gray-400 hover:text-white text-sm leading-none transition-colors disabled:opacity-40">+</button>
                                    </div>
                                    <button @click="toggle(d)"
                                            :disabled="!!sending[d.id]"
                                            class="relative inline-flex items-center h-5 w-9 rounded-full transition-colors focus:outline-none disabled:opacity-50 disabled:cursor-not-allowed"
                                            :class="isActive(d) ? 'bg-blue-600' : 'bg-gray-700'">
                                        <span class="inline-block w-3.5 h-3.5 transform rounded-full bg-white transition-transform"
                                              :class="isActive(d) ? 'translate-x-4' : 'translate-x-1'"></span>
                                    </button>
                                </template>

                                <template v-else-if="d.type === 'blind'">
                                    <div class="flex gap-2">
                                        <button @click="sendCommand(d, 'open')"
                                                :disabled="d.status === 'open' || !!sending[d.id]"
                                                class="text-xs font-semibold px-3 py-1.5 rounded-lg border transition-colors
                                                       disabled:opacity-30 disabled:cursor-not-allowed
                                                       border-green-800/60 text-green-400 hover:bg-green-900/30">
                                            Abrir
                                        </button>
                                        <button @click="sendCommand(d, 'close')"
                                                :disabled="d.status === 'closed' || !!sending[d.id]"
                                                class="text-xs font-semibold px-3 py-1.5 rounded-lg border transition-colors
                                                       disabled:opacity-30 disabled:cursor-not-allowed
                                                       border-gray-700 text-gray-400 hover:bg-gray-800">
                                            Cerrar
                                        </button>
                                    </div>
                                </template>

                                <template v-else-if="isControllable(d.type)">
                                    <button @click="toggle(d)"
                                            :disabled="!!sending[d.id]"
                                            class="relative inline-flex items-center h-5 w-9 rounded-full transition-colors focus:outline-none disabled:opacity-50 disabled:cursor-not-allowed"
                                            :class="isActive(d) ? 'bg-blue-600' : 'bg-gray-700'">
                                        <span class="inline-block w-3.5 h-3.5 transform rounded-full bg-white transition-transform"
                                              :class="isActive(d) ? 'translate-x-4' : 'translate-x-1'"></span>
                                    </button>
                                </template>

                                <span v-else class="text-xs text-gray-600">Read only</span>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>

            <!-- Pagination -->
            <div v-if="pageCount > 1 || filtered.length > 0"
                 class="flex items-center justify-between px-4 py-3 border-t border-gray-800">
                <p class="text-xs text-gray-500">
                    {{ filtered.length }} device{{ filtered.length !== 1 ? 's' : '' }}
                    <template v-if="filtered.length !== devices.length"> (filtered from {{ devices.length }})</template>
                </p>
                <div class="flex items-center gap-2">
                    <button @click="page--" :disabled="page === 1"
                            class="px-2.5 py-1 text-xs rounded-lg border border-gray-700 text-gray-400
                                   hover:text-white hover:border-gray-600 transition-colors
                                   disabled:opacity-30 disabled:cursor-not-allowed">
                        ‹ Prev
                    </button>
                    <span class="text-xs text-gray-500">{{ page }} / {{ pageCount }}</span>
                    <button @click="page++" :disabled="page === pageCount"
                            class="px-2.5 py-1 text-xs rounded-lg border border-gray-700 text-gray-400
                                   hover:text-white hover:border-gray-600 transition-colors
                                   disabled:opacity-30 disabled:cursor-not-allowed">
                        Next ›
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, watch, onMounted } from 'vue'
import axios from 'axios'

const SortIcon = {
    props: ['col', 'sortKey', 'sortDir'],
    template: `
        <span class="inline-flex flex-col -space-y-1 ml-0.5">
            <i class="ri-arrow-up-s-fill text-[11px] leading-none transition-colors"
               :class="sortKey === col && sortDir === 'asc' ? 'text-blue-400' : 'text-gray-600'"></i>
            <i class="ri-arrow-down-s-fill text-[11px] leading-none transition-colors"
               :class="sortKey === col && sortDir === 'desc' ? 'text-blue-400' : 'text-gray-600'"></i>
        </span>
    `,
}

const PAGE_SIZE = 20

const props = defineProps({
    addressId: { type: String, required: true },
})

const devices    = ref([])
const loading    = ref(true)
const error      = ref(null)
const sending    = ref({})

const search     = ref('')
const typeFilter = ref('')
const sortKey    = ref('id')
const sortDir    = ref('asc')
const page       = ref(1)

const filtered = computed(() => {
    const q    = search.value.trim().toLowerCase()
    const type = typeFilter.value
    let list   = devices.value
    if (q)    list = list.filter(d => d.name.toLowerCase().includes(q))
    if (type) list = list.filter(d => d.type === type)
    return [...list].sort((a, b) => {
        const av = sortKey.value === 'name' ? a.name.toLowerCase()
                 : sortKey.value === 'type' ? (labels[a.type] ?? a.type).toLowerCase()
                 : sortKey.value === 'status' ? a.status.toLowerCase()
                 : a.id
        const bv = sortKey.value === 'name' ? b.name.toLowerCase()
                 : sortKey.value === 'type' ? (labels[b.type] ?? b.type).toLowerCase()
                 : sortKey.value === 'status' ? b.status.toLowerCase()
                 : b.id
        if (av < bv) return sortDir.value === 'asc' ? -1 : 1
        if (av > bv) return sortDir.value === 'asc' ?  1 : -1
        return 0
    })
})

const pageCount = computed(() => Math.max(1, Math.ceil(filtered.value.length / PAGE_SIZE)))
const paginated = computed(() => filtered.value.slice((page.value - 1) * PAGE_SIZE, page.value * PAGE_SIZE))

watch([search, typeFilter, sortKey, sortDir], () => { page.value = 1 })

function setSort(key) {
    if (sortKey.value === key) sortDir.value = sortDir.value === 'asc' ? 'desc' : 'asc'
    else { sortKey.value = key; sortDir.value = 'asc' }
}

const labels = {
    light:         'Light',
    blind:         'Blind',
    door_sensor:   'Door sensor',
    window_sensor: 'Window sensor',
    camera:        'Camera',
    heating:       'Heating',
    smart_outlet: 'Smart outlet',
    gas: 'Gas sensor',
}

const icons = {
    light:         'M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z',
    blind:         'M4 6h16M4 10h16M4 14h16M4 18h16',
    door_sensor:   'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6',
    window_sensor: 'M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2',
    camera:        'M15 10l4.553-2.069A1 1 0 0121 8.82v6.362a1 1 0 01-1.447.894L15 14M3 8a2 2 0 012-2h10a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8z',
    heating:       'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z',
}

const activeStatuses = ['on', 'open', 'active']
const controllable   = ['light', 'blind', 'heating']

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

function isActive(d)       { return activeStatuses.includes(d.status) }
function isControllable(t) { return controllable.includes(t) }
function capitalize(s)     { return s ? s.charAt(0).toUpperCase() + s.slice(1) : '' }

async function load() {
    loading.value = true
    error.value   = null
    try {
        const { data } = await axios.get(`/api/devices/${props.addressId}`)
        devices.value = data.data
        page.value    = 1
    } catch {
        error.value = 'Could not load devices.'
    } finally {
        loading.value = false
    }
}

async function calibrateHeating(d, patch) {
    const prevStatus   = d.status
    const prevSettings = { ...(d.settings ?? {}) }
    const payload = {
        heating_status:     patch.heating_status     ?? prevStatus,
        target_temperature: patch.target_temperature ?? prevSettings.target_temperature ?? 68,
        heating_mode:       prevSettings.heating_mode ?? 'manual',
    }
    if (patch.heating_status)              d.status = patch.heating_status
    if (patch.target_temperature !== undefined) d.settings = { ...prevSettings, target_temperature: patch.target_temperature }
    sending.value[d.id] = true
    try {
        const { data } = await axios.patch(`/api/heating/${props.addressId}/calibrate`, payload)
        d.status   = data.heating_status
        d.settings = { target_temperature: data.target_temperature, heating_mode: data.heating_mode }
    } catch {
        d.status   = prevStatus
        d.settings = prevSettings
    } finally {
        delete sending.value[d.id]
    }
}

async function toggle(d) {
    if (d.type === 'heating') {
        await calibrateHeating(d, { heating_status: isActive(d) ? 'off' : 'on' })
        return
    }
    const action     = actionFor[d.status]
    const prevStatus = d.status
    d.status = statusFor[action]
    sending.value[d.id] = true
    try {
        const { data } = await axios.post('/api/commands', { device_id: d.id, action })
        d.status   = data.device.status
        d.settings = data.device.settings
    } catch {
        d.status = prevStatus
    } finally {
        delete sending.value[d.id]
    }
}

async function adjustTemperature(d, delta) {
    const current = d.settings?.target_temperature ?? 68
    const target  = Math.min(122, Math.max(32, current + delta))
    await calibrateHeating(d, { target_temperature: target })
}

async function sendCommand(d, action) {
    const prevStatus = d.status
    d.status = statusFor[action]
    sending.value[d.id] = true
    try {
        const { data } = await axios.post('/api/commands', { device_id: d.id, action })
        d.status   = data.device.status
        d.settings = data.device.settings
    } catch {
        d.status = prevStatus
    } finally {
        delete sending.value[d.id]
    }
}

async function adjustPosition(d, delta) {
    const current = d.settings?.position ?? 50
    const target  = Math.min(100, Math.max(0, current + delta))
    const prev    = { ...(d.settings ?? {}) }
    d.settings    = { ...prev, position: target }
    sending.value[d.id] = true
    try {
        const { data } = await axios.post('/api/commands', {
            device_id: d.id,
            action:    'set_position',
            settings:  { position: target },
        })
        d.settings = data.device.settings
    } catch {
        d.settings = prev
    } finally {
        delete sending.value[d.id]
    }
}

onMounted(load)
</script>
