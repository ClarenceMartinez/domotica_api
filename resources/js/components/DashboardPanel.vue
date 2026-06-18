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

            <button @click="onAddressChange"
                    :disabled="!addressId || loading"
                    title="Refresh"
                    class="flex items-center justify-center w-9 h-9 rounded-lg border border-gray-700 text-gray-400 hover:text-white hover:border-gray-500 transition-colors disabled:opacity-30 disabled:cursor-not-allowed">
                <svg class="w-4 h-4" :class="{ 'animate-spin': loading }"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
            </button>
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
                            <h3 class="text-sm font-bold text-white uppercase tracking-wide">Persianas</h3>
                        </div>
                        <div v-if="blinds.length === 0" class="flex-1 flex items-center justify-center">
                            <p class="text-xs text-gray-600">No hay persianas registradas</p>
                        </div>
                        <div v-else class="flex-1 flex flex-col justify-between">
                            <div class="mb-4">
                                <p class="text-xs text-gray-500 mb-1">Estado general</p>
                                <p class="text-sm font-semibold" :class="blindsStatus.color">
                                    Persianas: {{ blindsStatus.text }}
                                </p>
                            </div>
                            <div class="space-y-2 mt-auto">
                                <button @click="openAllBlinds"
                                        :disabled="blindsAllOpen || blindsLoading"
                                        class="w-full text-xs font-semibold py-2 rounded-lg border transition-colors
                                               disabled:opacity-30 disabled:cursor-not-allowed
                                               border-green-800/60 text-green-400 hover:bg-green-900/30">
                                    {{ blindsLoading ? 'Abriendo…' : 'Abrir todas' }}
                                </button>
                                <button @click="blindsModal = true"
                                        class="w-full text-xs font-semibold py-2 rounded-lg border transition-colors
                                               border-blue-900/60 text-blue-400 hover:bg-blue-900/20">
                                    Administrar persianas
                                </button>
                            </div>
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

                <!-- OUTLETS + GAS -->
                <div class="grid grid-cols-2 gap-4 mb-4">

                <!-- SMART OUTLETS -->
                <div class="bg-[#111827] border rounded-xl p-5 transition-colors"
                     :class="{
                         'border-red-900/60':    outletState === 'risk',
                         'border-yellow-900/40': outletState === 'contained',
                         'border-gray-800':      outletState === 'normal',
                     }">

                    <!-- Normal -->
                    <template v-if="outletState === 'normal'">
                        <div class="flex items-center gap-2 mb-4">
                            <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                            <h3 class="text-sm font-bold text-white uppercase tracking-wide">Smart Outlets</h3>
                        </div>
                        <div v-if="outletReadings.length === 0" class="py-6 text-center">
                            <p class="text-xs text-gray-600">No outlet data received yet.</p>
                        </div>
                        <template v-else>
                            <table class="w-full text-xs mb-4">
                                <thead>
                                    <tr class="border-b border-gray-800">
                                        <th class="pb-2 text-left text-gray-500 font-medium uppercase tracking-wider">Outlet</th>
                                        <th class="pb-2 text-right text-gray-500 font-medium uppercase tracking-wider">Power</th>
                                        <th class="pb-2 text-right text-gray-500 font-medium uppercase tracking-wider">Current</th>
                                        <th class="pb-2 text-right text-gray-500 font-medium uppercase tracking-wider">Status</th>
                                        <th class="pb-2 text-right text-gray-500 font-medium uppercase tracking-wider">Risk</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-800/60">
                                    <tr v-for="r in outletReadings" :key="r.id">
                                        <td class="py-2.5 text-gray-300">{{ r.outlet_name }}</td>
                                        <td class="py-2.5 text-right text-gray-300">{{ r.power_watts }} W</td>
                                        <td class="py-2.5 text-right text-gray-400">{{ (r.power_watts / 110).toFixed(2) }} A</td>
                                        <td class="py-2.5 text-right">
                                            <span :class="r.outlet_status === 'on' ? 'text-green-400' : 'text-gray-500'">
                                                {{ r.outlet_status === 'on' ? 'On' : 'Off' }}
                                            </span>
                                        </td>
                                        <td class="py-2.5 text-right">
                                            <span :class="r.risk_level === 'high' ? 'text-red-400' : r.risk_level === 'medium' ? 'text-yellow-400' : 'text-gray-400'">
                                                {{ capitalize(r.risk_level) }}
                                            </span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="grid grid-cols-3 gap-3 mb-4">
                                <div class="bg-gray-800/40 rounded-lg px-3 py-2.5">
                                    <p class="text-[10px] text-gray-500 uppercase tracking-wider mb-0.5">Total Power</p>
                                    <p class="text-sm font-semibold text-white">{{ outletTotals.watts }} W</p>
                                </div>
                                <div class="bg-gray-800/40 rounded-lg px-3 py-2.5">
                                    <p class="text-[10px] text-gray-500 uppercase tracking-wider mb-0.5">Total Current</p>
                                    <p class="text-sm font-semibold text-white">{{ outletTotals.amps }} A</p>
                                </div>
                                <div class="bg-gray-800/40 rounded-lg px-3 py-2.5">
                                    <p class="text-[10px] text-gray-500 uppercase tracking-wider mb-0.5">Voltage</p>
                                    <p class="text-sm font-semibold text-white">110 V</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                                <p class="text-xs text-green-400">No electrical risk detected</p>
                            </div>
                        </template>
                    </template>

                    <!-- Risk detected -->
                    <template v-else-if="outletState === 'risk'">
                        <div class="flex items-center justify-between mb-5">
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                                <h3 class="text-sm font-bold text-red-400 uppercase tracking-wide">Electrical Safety</h3>
                            </div>
                            <span class="text-[10px] font-semibold bg-red-900/40 text-red-400 border border-red-800/40 px-2.5 py-1 rounded-full">Alert</span>
                        </div>
                        <div class="mb-5">
                            <p class="text-base font-bold text-white mb-1">Risk detected</p>
                            <p class="text-xs text-gray-400">
                                {{ highRiskReading?.outlet_name }} outlet exceeded safe power usage for
                                <span class="text-red-400 font-medium">{{ highRiskReading?.minutes_over_limit }}</span> minutes.
                            </p>
                        </div>
                        <div class="grid grid-cols-3 gap-3 mb-5">
                            <div class="bg-red-900/10 border border-red-900/30 rounded-lg p-3">
                                <div class="flex items-center gap-1.5 mb-1.5">
                                    <svg class="w-3.5 h-3.5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                    </svg>
                                    <p class="text-[10px] text-gray-500 uppercase tracking-wider">Outlet</p>
                                </div>
                                <p class="text-xs font-semibold text-white">{{ highRiskReading?.outlet_name }}</p>
                            </div>
                            <div class="bg-red-900/10 border border-red-900/30 rounded-lg p-3">
                                <p class="text-[10px] text-gray-500 uppercase tracking-wider mb-1.5">Power Usage</p>
                                <p class="text-xs font-semibold text-red-400">{{ capitalize(highRiskReading?.risk_level ?? '') }}</p>
                            </div>
                            <div class="bg-red-900/10 border border-red-900/30 rounded-lg p-3">
                                <div class="flex items-center gap-1.5 mb-1.5">
                                    <svg class="w-3.5 h-3.5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <p class="text-[10px] text-gray-500 uppercase tracking-wider">Time-over limit</p>
                                </div>
                                <p class="text-xs font-semibold text-red-400">{{ highRiskReading?.minutes_on }} min</p>
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 mb-4">Action recommended: Turn off outlet.</p>
                        <div class="flex flex-col gap-2">
                            <button @click="turnOffRiskyOutlet"
                                    class="w-full py-2.5 text-xs font-semibold text-white bg-red-600 hover:bg-red-700 rounded-lg transition-colors">
                                Turn Off Outlet
                            </button>
                            <button @click="detailsModal = true"
                                    class="w-full py-2.5 text-xs font-semibold text-gray-400 border border-gray-700 hover:border-gray-600 hover:text-white rounded-lg transition-colors">
                                View Device Details
                            </button>
                        </div>
                    </template>

                    <!-- Risk contained -->
                    <template v-else>
                        <button @click="incidentModal = true" class="w-full text-left">
                            <div class="flex items-center gap-2 mb-4">
                                <svg class="w-5 h-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                </svg>
                                <h3 class="text-sm font-bold text-yellow-400 uppercase tracking-wide">Risk Contained</h3>
                            </div>
                            <div class="grid grid-cols-2 gap-3 mb-3">
                                <div class="bg-gray-800/40 rounded-lg px-3 py-2.5">
                                    <p class="text-[10px] text-gray-500 uppercase tracking-wider mb-0.5">Power State</p>
                                    <p class="text-sm font-semibold text-white">Off</p>
                                </div>
                                <div class="bg-gray-800/40 rounded-lg px-3 py-2.5">
                                    <p class="text-[10px] text-gray-500 uppercase tracking-wider mb-0.5">Risk State</p>
                                    <p class="text-sm font-semibold text-yellow-400">Contained</p>
                                </div>
                            </div>
                            <p class="text-xs text-gray-500">{{ minutesAgo === 0 ? 'Just now' : minutesAgo + ' minutes ago' }}</p>
                        </button>
                    </template>

                </div>

                <!-- GAS SAFETY -->
                <div class="bg-[#111827] border rounded-xl p-5 transition-colors flex flex-col"
                     :class="{
                         'border-red-900/60': gasState === 'danger',
                         'border-gray-800':   gasState === 'normal',
                     }">

                    <!-- Normal state -->
                    <template v-if="gasState === 'normal'">
                        <div class="flex items-center gap-2 mb-4">
                            <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.879 16.121A3 3 0 1012.015 11L11 14H9c0 .768.293 1.536.879 2.121z"/>
                            </svg>
                            <h3 class="text-sm font-bold text-white uppercase tracking-wide">Gas Safety</h3>
                        </div>
                        <div v-if="gasReadings.length === 0" class="flex-1 flex items-center justify-center py-6">
                            <p class="text-xs text-gray-600">No gas sensor data received yet.</p>
                        </div>
                        <template v-else>
                            <ul class="space-y-3 flex-1 mb-4">
                                <li v-for="r in gasReadingsWithName" :key="r.id" class="bg-gray-800/40 rounded-lg px-3 py-2.5">
                                    <p class="text-sm font-medium text-white mb-0.5">{{ r.device_name }}</p>
                                    <p class="text-xs text-green-400">Status: Normal</p>
                                    <p class="text-xs text-gray-600 mt-0.5">Last reading: {{ gasTimeLabel(r.created_at) }}</p>
                                </li>
                            </ul>
                            <div class="flex items-center gap-1.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                                <p class="text-xs text-green-400">No gas leak detected</p>
                            </div>
                        </template>
                    </template>

                    <!-- Danger state -->
                    <template v-else>
                        <div class="flex items-center justify-between mb-5">
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.879 16.121A3 3 0 1012.015 11L11 14H9c0 .768.293 1.536.879 2.121z"/>
                                </svg>
                                <h3 class="text-sm font-bold text-red-400 uppercase tracking-wide">GAS LEAK</h3>
                            </div>
                            <span class="text-[10px] font-semibold bg-red-900/40 text-red-400 border border-red-800/40 px-2.5 py-1 rounded-full animate-pulse">Danger</span>
                        </div>
                        <div class="grid grid-cols-2 gap-3 mb-5">
                            <div class="bg-red-900/10 border border-red-900/30 rounded-lg p-3">
                                <p class="text-[10px] text-gray-500 uppercase tracking-wider mb-1">State</p>
                                <p class="text-xs font-semibold text-red-400">Danger</p>
                            </div>
                            <div class="bg-red-900/10 border border-red-900/30 rounded-lg p-3">
                                <p class="text-[10px] text-gray-500 uppercase tracking-wider mb-1">Sensor</p>
                                <p class="text-xs font-semibold text-white">{{ gasReadingsWithName.find(r => r.gas_status === 'danger')?.device_name ?? '—' }}</p>
                            </div>
                            <div class="bg-red-900/10 border border-red-900/30 rounded-lg p-3">
                                <p class="text-[10px] text-gray-500 uppercase tracking-wider mb-1">Gas value</p>
                                <p class="text-xs font-semibold text-red-400">{{ dangerGasReading?.gas_value ?? '—' }} ppm</p>
                            </div>
                            <div class="bg-red-900/10 border border-red-900/30 rounded-lg p-3">
                                <p class="text-[10px] text-gray-500 uppercase tracking-wider mb-1">Last reading</p>
                                <p class="text-xs font-semibold text-white">{{ dangerGasReading ? gasTimeLabel(dangerGasReading.created_at) : '—' }}</p>
                            </div>
                        </div>
                        <div class="bg-red-900/10 border border-red-800/40 rounded-xl p-4">
                            <p class="text-sm font-bold text-red-400 mb-0.5">Gas leak detected</p>
                            <p class="text-xs text-red-600 mb-4">Required immediate action!</p>
                            <button @click="gasModal = true"
                                    class="w-full py-2.5 text-xs font-semibold text-white bg-red-600 hover:bg-red-700 rounded-lg transition-colors">
                                Emergency shutdown
                            </button>
                        </div>
                    </template>

                </div>

                </div><!-- end OUTLETS + GAS grid -->

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

    <!-- Device details modal (risk state) -->
    <Teleport to="body">
        <div v-if="detailsModal" class="fixed inset-0 z-50 flex items-center justify-center">
            <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="detailsModal = false"></div>
            <div class="relative bg-[#111827] border border-gray-700 rounded-2xl p-6 w-full max-w-md shadow-xl">
                <div class="flex items-center justify-between mb-5">
                    <h2 class="text-sm font-bold text-white uppercase tracking-wide">Device Details</h2>
                    <button @click="detailsModal = false" class="text-gray-600 hover:text-gray-400 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                <dl class="grid grid-cols-2 gap-x-6 gap-y-3 text-xs">
                    <div><dt class="text-gray-500 mb-0.5">Outlet Name</dt><dd class="text-white font-medium">{{ highRiskReading?.outlet_name }}</dd></div>
                    <div><dt class="text-gray-500 mb-0.5">Status</dt><dd class="text-white font-medium capitalize">{{ highRiskReading?.outlet_status }}</dd></div>
                    <div><dt class="text-gray-500 mb-0.5">Power</dt><dd class="text-white font-medium">{{ highRiskReading?.power_watts }} W</dd></div>
                    <div><dt class="text-gray-500 mb-0.5">Current</dt><dd class="text-white font-medium">{{ ((highRiskReading?.power_watts ?? 0) / 110).toFixed(2) }} A</dd></div>
                    <div><dt class="text-gray-500 mb-0.5">Minutes On</dt><dd class="text-white font-medium">{{ highRiskReading?.minutes_on }} min</dd></div>
                    <div><dt class="text-gray-500 mb-0.5">Max Safe</dt><dd class="text-white font-medium">{{ highRiskReading?.max_safe_minutes }} min</dd></div>
                    <div><dt class="text-gray-500 mb-0.5">Over Limit</dt><dd class="text-red-400 font-medium">{{ highRiskReading?.minutes_over_limit }} min</dd></div>
                    <div><dt class="text-gray-500 mb-0.5">Risk Level</dt><dd class="text-red-400 font-medium capitalize">{{ highRiskReading?.risk_level }}</dd></div>
                    <div><dt class="text-gray-500 mb-0.5">Energy Status</dt><dd class="text-white font-medium">{{ highRiskReading?.energy_status }}</dd></div>
                    <div><dt class="text-gray-500 mb-0.5">Appliance Type</dt><dd class="text-white font-medium">{{ highRiskReading?.appliance_type ?? '—' }}</dd></div>
                    <div class="col-span-2"><dt class="text-gray-500 mb-0.5">Message</dt><dd class="text-white font-medium">{{ highRiskReading?.message ?? '—' }}</dd></div>
                </dl>
                <button @click="detailsModal = false"
                        class="mt-5 w-full py-2.5 text-xs font-semibold text-gray-400 border border-gray-700 hover:border-gray-600 rounded-lg transition-colors">
                    Close
                </button>
            </div>
        </div>
    </Teleport>

    <!-- Incident modal (contained state) -->
    <Teleport to="body">
        <div v-if="incidentModal" class="fixed inset-0 z-50 flex items-center justify-center">
            <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="incidentModal = false"></div>
            <div class="relative bg-[#111827] border border-gray-700 rounded-2xl p-6 w-full max-w-sm shadow-xl">
                <div class="flex items-center justify-between mb-5">
                    <h2 class="text-sm font-bold text-white uppercase tracking-wide">Incident History</h2>
                    <button @click="incidentModal = false" class="text-gray-600 hover:text-gray-400 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                <ol class="space-y-3 mb-4 text-xs">
                    <li class="flex gap-3">
                        <span class="w-5 h-5 rounded-full bg-red-900/40 border border-red-800/40 text-red-400 flex items-center justify-center text-[10px] font-bold shrink-0">1</span>
                        <p class="text-gray-300 pt-0.5">{{ containedOutlet?.outlet_name }} outlet power disconnected.</p>
                    </li>
                    <li class="flex gap-3">
                        <span class="w-5 h-5 rounded-full bg-yellow-900/40 border border-yellow-800/40 text-yellow-400 flex items-center justify-center text-[10px] font-bold shrink-0">2</span>
                        <p class="text-gray-300 pt-0.5">Risk state changed to Contained.</p>
                    </li>
                    <li class="flex gap-3">
                        <span class="w-5 h-5 rounded-full bg-gray-800 border border-gray-700 text-gray-400 flex items-center justify-center text-[10px] font-bold shrink-0">3</span>
                        <p class="text-gray-300 pt-0.5">Alert sent to homeowner.</p>
                    </li>
                    <li class="flex gap-3">
                        <span class="w-5 h-5 rounded-full bg-gray-800 border border-gray-700 text-gray-400 flex items-center justify-center text-[10px] font-bold shrink-0">4</span>
                        <p class="text-gray-300 pt-0.5">Incident recorded in activity log.</p>
                    </li>
                    <li class="flex gap-3">
                        <span class="w-5 h-5 rounded-full bg-gray-800 border border-gray-700 text-gray-400 flex items-center justify-center text-[10px] font-bold shrink-0">5</span>
                        <p class="text-gray-300 pt-0.5">Follow-up review recommended.</p>
                    </li>
                </ol>
                <p class="text-xs text-gray-600 mb-5">
                    Occurred {{ minutesAgo === 0 ? 'just now' : minutesAgo + ' minutes ago' }}.
                </p>
                <div class="flex gap-2">
                    <button @click="acknowledgeRisk"
                            class="flex-1 py-2.5 text-xs font-semibold text-white bg-blue-600 hover:bg-blue-500 rounded-lg transition-colors">
                        Acknowledge
                    </button>
                    <button @click="incidentLogModal = true"
                            class="flex-1 py-2.5 text-xs font-semibold text-gray-400 border border-gray-700 hover:border-gray-600 hover:text-white rounded-lg transition-colors">
                        View Incident Log
                    </button>
                </div>
            </div>
        </div>
    </Teleport>

    <!-- Incident log modal -->
    <Teleport to="body">
        <div v-if="incidentLogModal" class="fixed inset-0 z-50 flex items-center justify-center">
            <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="incidentLogModal = false"></div>
            <div class="relative bg-[#111827] border border-gray-700 rounded-2xl p-6 w-full max-w-md shadow-xl max-h-[80vh] overflow-y-auto">
                <div class="flex items-center justify-between mb-5">
                    <h2 class="text-sm font-bold text-white uppercase tracking-wide">Incident Log</h2>
                    <button @click="incidentLogModal = false" class="text-gray-600 hover:text-gray-400 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                <div class="space-y-3 text-xs">
                    <div class="bg-red-900/10 border border-red-900/30 rounded-lg p-3">
                        <p class="text-[10px] text-gray-500 uppercase tracking-wider mb-1">Device</p>
                        <p class="text-white font-medium">{{ containedOutlet?.outlet_name }}</p>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div class="bg-gray-800/40 rounded-lg p-3">
                            <p class="text-[10px] text-gray-500 uppercase tracking-wider mb-1">Power at incident</p>
                            <p class="text-white font-medium">{{ containedOutlet?.power_watts }} W</p>
                        </div>
                        <div class="bg-gray-800/40 rounded-lg p-3">
                            <p class="text-[10px] text-gray-500 uppercase tracking-wider mb-1">Time over limit</p>
                            <p class="text-red-400 font-medium">{{ containedOutlet?.minutes_over_limit }} min</p>
                        </div>
                        <div class="bg-gray-800/40 rounded-lg p-3">
                            <p class="text-[10px] text-gray-500 uppercase tracking-wider mb-1">Risk Level</p>
                            <p class="text-red-400 font-medium capitalize">{{ containedOutlet?.risk_level }}</p>
                        </div>
                        <div class="bg-gray-800/40 rounded-lg p-3">
                            <p class="text-[10px] text-gray-500 uppercase tracking-wider mb-1">Energy Status</p>
                            <p class="text-white font-medium">{{ containedOutlet?.energy_status }}</p>
                        </div>
                    </div>
                    <div class="bg-gray-800/40 rounded-lg p-3">
                        <p class="text-[10px] text-gray-500 uppercase tracking-wider mb-1">Message</p>
                        <p class="text-white">{{ containedOutlet?.message ?? '—' }}</p>
                    </div>
                    <div class="bg-gray-800/40 rounded-lg p-3">
                        <p class="text-[10px] text-gray-500 uppercase tracking-wider mb-1">Appliance Type</p>
                        <p class="text-white">{{ containedOutlet?.appliance_type ?? '—' }}</p>
                    </div>
                </div>
                <p class="text-xs text-gray-600 mt-4 mb-5">
                    Occurred {{ minutesAgo === 0 ? 'just now' : minutesAgo + ' minutes ago' }}.
                </p>
                <button @click="incidentLogModal = false"
                        class="w-full py-2.5 text-xs font-semibold text-gray-400 border border-gray-700 hover:border-gray-600 rounded-lg transition-colors">
                    Close
                </button>
            </div>
        </div>
    </Teleport>

    <!-- Blinds management modal -->
    <Teleport to="body">
        <div v-if="blindsModal" class="fixed inset-0 z-50 flex items-center justify-center">
            <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="blindsModal = false"></div>
            <div class="relative bg-[#111827] border border-gray-700 rounded-2xl p-6 w-full max-w-sm shadow-xl">

                <div class="flex items-center justify-between mb-5">
                    <h2 class="text-sm font-bold text-white uppercase tracking-wide">Administrar persianas</h2>
                    <button @click="blindsModal = false" class="text-gray-600 hover:text-gray-400 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <ul class="space-y-4">
                    <li v-for="d in blinds" :key="d.id" class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-white">{{ d.name }}</p>
                            <p class="text-xs" :class="d.status === 'open' ? 'text-green-400' : 'text-gray-500'">
                                Estado: {{ d.status === 'open' ? 'Abierta' : 'Cerrada' }}
                            </p>
                        </div>
                        <button @click="sendCommand(d, d.status === 'open' ? 'close' : 'open')"
                                :disabled="!!sending[d.id]"
                                class="text-xs font-semibold px-3 py-1.5 rounded-lg border transition-colors disabled:opacity-40"
                                :class="d.status === 'open'
                                    ? 'border-gray-700 text-gray-400 hover:bg-gray-800'
                                    : 'border-green-800/60 text-green-400 hover:bg-green-900/30'">
                            {{ d.status === 'open' ? 'Cerrar' : 'Abrir' }}
                        </button>
                    </li>
                </ul>

            </div>
        </div>
    </Teleport>

    <!-- Gas Emergency Shutdown Modal -->
    <Teleport to="body">
        <div v-if="gasModal" class="fixed inset-0 z-50 flex items-center justify-center">
            <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="gasModal = false"></div>
            <div class="relative bg-[#111827] border border-gray-700 rounded-2xl w-full max-w-lg shadow-xl max-h-[85vh] overflow-y-auto">

                <div class="sticky top-0 bg-[#111827] border-b border-gray-800 px-6 py-4 flex items-center justify-between rounded-t-2xl z-10">
                    <h2 class="text-sm font-bold text-white uppercase tracking-wide">Emergency Shutdown</h2>
                    <button @click="gasModal = false" class="text-gray-600 hover:text-gray-400 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <div class="p-6 space-y-4">

                    <!-- Risk card -->
                    <div class="bg-red-900/10 border border-red-800/40 rounded-xl p-4">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-8 h-8 rounded-full bg-red-900/40 flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-red-400">Gas leak detected</p>
                                <p class="text-xs text-gray-500">Immediate action required</p>
                            </div>
                        </div>
                        <div class="grid grid-cols-3 gap-3">
                            <div>
                                <p class="text-[10px] text-gray-500 uppercase tracking-wider mb-0.5">Risk level</p>
                                <p class="text-xs font-semibold text-red-400">Danger</p>
                            </div>
                            <div>
                                <p class="text-[10px] text-gray-500 uppercase tracking-wider mb-0.5">Sensor</p>
                                <p class="text-xs font-semibold text-white">{{ gasReadingsWithName.find(r => r.gas_status === 'danger')?.device_name ?? '—' }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] text-gray-500 uppercase tracking-wider mb-0.5">Last reading</p>
                                <p class="text-xs font-semibold text-white">{{ dangerGasReading ? gasTimeLabel(dangerGasReading.created_at) : '—' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Recommended actions -->
                    <div>
                        <p class="text-[10px] text-gray-500 uppercase tracking-wider mb-3">Recommended actions</p>
                        <div class="space-y-3">

                            <!-- 1. Close valve -->
                            <div class="bg-gray-800/40 border border-gray-700/50 rounded-xl p-4 flex items-center justify-between gap-3">
                                <div class="min-w-0">
                                    <p class="text-xs font-semibold text-white mb-0.5">1. Close main gas valve</p>
                                    <p class="text-[10px] text-gray-500">Stop gas flow immediately</p>
                                </div>
                                <button v-if="!valveClosed"
                                        @click="closeValve"
                                        :disabled="valveLoading"
                                        class="text-xs font-semibold px-4 py-2 rounded-lg bg-red-600 hover:bg-red-700 text-white transition-colors disabled:opacity-50 shrink-0">
                                    {{ valveLoading ? 'Sending…' : 'Close valve' }}
                                </button>
                                <span v-else class="flex items-center gap-1.5 text-xs text-green-400 shrink-0">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    Done
                                </span>
                            </div>

                            <!-- 2. Shutdown heating (auto) -->
                            <div class="bg-gray-800/40 border border-gray-700/50 rounded-xl p-4 flex items-center justify-between gap-3">
                                <div class="min-w-0">
                                    <p class="text-xs font-semibold text-white mb-0.5">2. Shutdown heating</p>
                                    <p class="text-[10px] text-gray-500">Prevent ignition sources</p>
                                </div>
                                <span class="flex items-center gap-1.5 text-xs text-green-400 shrink-0">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    Auto
                                </span>
                            </div>

                            <!-- 3. Shutdown outlets (auto) -->
                            <div class="bg-gray-800/40 border border-gray-700/50 rounded-xl p-4 flex items-center justify-between gap-3">
                                <div class="min-w-0">
                                    <p class="text-xs font-semibold text-white mb-0.5">3. Shutdown smart outlets</p>
                                    <p class="text-[10px] text-gray-500">Avoid sparks and fire risk</p>
                                </div>
                                <span class="flex items-center gap-1.5 text-xs text-green-400 shrink-0">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    Auto
                                </span>
                            </div>

                            <!-- 4. Open windows/blinds -->
                            <div class="bg-gray-800/40 border border-gray-700/50 rounded-xl p-4 flex items-center justify-between gap-3">
                                <div class="min-w-0">
                                    <p class="text-xs font-semibold text-white mb-0.5">4. Open windows / blinds</p>
                                    <p class="text-[10px] text-gray-500">Ventilate the house</p>
                                </div>
                                <button v-if="!blindsGasDone"
                                        @click="openBlindsForGas"
                                        :disabled="blindsGasLoading"
                                        class="text-xs font-semibold px-4 py-2 rounded-lg border border-blue-800/60 text-blue-400 hover:bg-blue-900/30 transition-colors disabled:opacity-50 shrink-0">
                                    {{ blindsGasLoading ? 'Opening…' : 'Open' }}
                                </button>
                                <span v-else class="flex items-center gap-1.5 text-xs text-green-400 shrink-0">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    Done
                                </span>
                            </div>

                            <!-- 5. Alarm activated -->
                            <div class="bg-red-900/10 border border-red-800/30 rounded-xl p-4 flex items-center justify-between gap-3">
                                <div class="min-w-0">
                                    <p class="text-xs font-semibold text-white mb-0.5">5. Activate alarm</p>
                                    <p class="text-[10px] text-gray-500">Alert all occupants</p>
                                </div>
                                <span class="text-[10px] font-semibold bg-red-900/40 text-red-400 border border-red-800/40 px-2.5 py-1 rounded-full animate-pulse shrink-0">
                                    Alarm activated
                                </span>
                            </div>

                            <!-- 6. Notify contacts -->
                            <div class="bg-gray-800/40 border border-gray-700/50 rounded-xl p-4 flex items-center justify-between gap-3">
                                <div class="min-w-0">
                                    <p class="text-xs font-semibold text-white mb-0.5">6. Notify contacts</p>
                                    <p class="text-[10px] text-gray-500">Alert emergency contacts</p>
                                </div>
                                <button @click="notifyContacts"
                                        class="text-xs font-semibold px-4 py-2 rounded-lg border border-gray-700 text-gray-400 hover:border-gray-600 hover:text-white transition-colors shrink-0">
                                    Notify contacts
                                </button>
                            </div>

                            <!-- 7. Call 112 -->
                            <div class="bg-gray-800/40 border border-gray-700/50 rounded-xl p-4 flex items-center justify-between gap-3">
                                <div class="min-w-0">
                                    <p class="text-xs font-semibold text-white mb-0.5">7. Call emergency services</p>
                                    <p class="text-[10px] text-gray-500">Contact fire brigade and gas company</p>
                                </div>
                                <a href="tel:112"
                                   class="text-xs font-semibold px-4 py-2 rounded-lg bg-orange-600 hover:bg-orange-700 text-white transition-colors shrink-0">
                                    Call 112
                                </a>
                            </div>

                        </div>
                    </div>

                    <!-- Evacuation card -->
                    <div class="bg-yellow-900/10 border border-yellow-800/30 rounded-xl p-4">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-8 h-8 rounded-full bg-yellow-900/40 flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-yellow-400">Evacuate the house immediately</p>
                                <p class="text-xs text-yellow-600">Don't turn on any lights or outlets.</p>
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 font-semibold mb-2">What to do now</p>
                        <ol class="space-y-2 text-xs text-gray-400">
                            <li class="flex gap-2"><span class="text-yellow-600 font-bold shrink-0">1.</span>Evacuate the house with all members.</li>
                            <li class="flex gap-2"><span class="text-yellow-600 font-bold shrink-0">2.</span>Don't use cell phone inside the house.</li>
                            <li class="flex gap-2"><span class="text-yellow-600 font-bold shrink-0">3.</span>Wait for emergency services to arrive outside the house.</li>
                        </ol>
                    </div>

                    <p class="text-xs text-gray-600 text-center">Stay in a safe location. The emergency services has been notified.</p>

                </div>
            </div>
        </div>
    </Teleport>

    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import axios from 'axios'

const props = defineProps({
    clients: { type: Array, default: () => [] },
})

const clientId  = ref('')
const addressId = ref('')
const devices   = ref([])
const loading   = ref(false)
const sending   = ref({})

const climate        = ref(null)
const temperature    = ref(null)
const outletReadings = ref([])

const consumption = computed(() => {
    if (outletReadings.value.length === 0) return null
    const activeWatts = outletReadings.value
        .filter(r => r.outlet_status === 'on')
        .reduce((sum, r) => sum + (r.power_watts ?? 0), 0)
    if (activeWatts === 0) return null
    return {
        current: (activeWatts / 1000).toFixed(2),
        today:   '--',
    }
})

const heatingModal   = ref(false)
const heatingForm    = ref({ target_temperature: 0, heating_mode: 'manual', heating_status: 'off' })
const heatingLoading = ref(false)

const blindsModal   = ref(false)
const blindsLoading = ref(false)

const containedOutlet  = ref(null)
const containedAt      = ref(null)
const detailsModal     = ref(false)
const incidentModal    = ref(false)
const incidentLogModal = ref(false)
const ticker           = ref(Date.now())

const gasReadings      = ref([])
const gasModal         = ref(false)
const valveClosed      = ref(false)
const valveLoading     = ref(false)
const blindsGasDone    = ref(false)
const blindsGasLoading = ref(false)

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

const blindsAllOpen = computed(() => blinds.value.length > 0 && blinds.value.every(d => d.status === 'open'))

const blindsStatus = computed(() => {
    if (blinds.value.length === 0) return { text: '—', color: 'text-gray-600' }
    const openCount = blinds.value.filter(d => d.status === 'open').length
    if (openCount === blinds.value.length) return { text: 'Abiertas', color: 'text-green-400' }
    if (openCount === 0)                   return { text: 'Cerradas', color: 'text-gray-400' }
    return { text: 'Mixtas', color: 'text-yellow-400' }
})

const highRiskReading = computed(() =>
    outletReadings.value.find(r => r.risk_level === 'high') ?? null
)

const outletState = computed(() => {
    if (containedOutlet.value) return 'contained'
    if (highRiskReading.value) return 'risk'
    return 'normal'
})

const outletTotals = computed(() => {
    const watts = outletReadings.value
        .filter(r => r.outlet_status === 'on')
        .reduce((sum, r) => sum + (r.power_watts ?? 0), 0)
    return { watts, amps: (watts / 110).toFixed(2) }
})

const minutesAgo = computed(() => {
    ticker.value // suscribe al ticker para que se recalcule cada minuto
    if (!containedAt.value) return 0
    return Math.floor((Date.now() - containedAt.value.getTime()) / 60000)
})

const homeStatus = computed(() => {
    if (gasState.value === 'danger')
        return { text: 'Gas leak', detail: 'Emergency: gas leak detected', safe: false }

    if (outletState.value === 'risk')
        return { text: 'Electrical risk', detail: 'High power usage detected', safe: false }

    const sensors = devices.value.filter(d => ['door_sensor', 'window_sensor'].includes(d.type))
    const alerts  = sensors.filter(d => d.status === 'open' || d.status === 'active')
    if (alerts.length > 0)
        return {
            text:   `${alerts.length} alert${alerts.length > 1 ? 's' : ''}`,
            detail: alerts.map(d => d.name).join(', '),
            safe:   false,
        }

    const hasData = sensors.length > 0 || gasReadings.value.length > 0 || outletReadings.value.length > 0
    if (!hasData)
        return { text: 'No sensors', detail: 'No sensors configured', safe: true }

    return { text: 'All secure', detail: 'No active alerts', safe: true }
})

const gasState = computed(() =>
    gasReadings.value.some(r => r.gas_status === 'danger') ? 'danger' : 'normal'
)

const dangerGasReading = computed(() =>
    gasReadings.value.find(r => r.gas_status === 'danger') ?? null
)

const gasReadingsWithName = computed(() =>
    gasReadings.value.map(r => ({
        ...r,
        device_name: devices.value.find(d => d.id === r.device_id)?.name ?? r.sensor,
    }))
)

function isActive(d)   { return activeStatuses.includes(d.status) }
function capitalize(s) { return s ? s.charAt(0).toUpperCase() + s.slice(1) : '' }

function gasTimeLabel(dateStr) {
    ticker.value
    const mins = Math.floor((Date.now() - new Date(dateStr).getTime()) / 60000)
    if (mins === 0) return 'Just now'
    return mins === 1 ? '1 minute ago' : `${mins} minutes ago`
}

function onClientChange() {
    addressId.value       = ''
    devices.value         = []
    climate.value         = null
    outletReadings.value  = []
    containedOutlet.value = null
    containedAt.value     = null
    temperature.value     = null
    gasReadings.value     = []
    valveClosed.value     = false
    blindsGasDone.value   = false
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

async function fetchOutlets(addrId) {
    try {
        const { data } = await axios.get(`/api/outlets/${addrId}`)
        outletReadings.value = data
    } catch {
        outletReadings.value = []
    }
}

async function fetchGas(addrId) {
    try {
        const { data } = await axios.get(`/api/gas/${addrId}`)
        gasReadings.value = data
    } catch {
        gasReadings.value = []
    }
}

async function closeValve() {
    valveLoading.value = true
    try {
        await axios.post(`/api/gas/${addressId.value}/close-valve`, {
            device_id: dangerGasReading.value?.device_id ?? null,
        })
        valveClosed.value = true
    } catch { /* ok */ } finally {
        valveLoading.value = false
    }
}

async function openBlindsForGas() {
    blindsGasLoading.value = true
    try {
        await axios.patch(`/api/blinds/${addressId.value}/open-all`)
        blindsGasDone.value = true
        blinds.value.forEach(d => { d.status = 'open' })
    } catch { /* ok */ } finally {
        blindsGasLoading.value = false
    }
}

function notifyContacts() {
    // placeholder - not implemented yet
}

async function onAddressChange() {
    if (!addressId.value) return
    loading.value         = true
    devices.value         = []
    climate.value         = null
    outletReadings.value  = []
    containedOutlet.value = null
    containedAt.value     = null
    temperature.value     = null
    gasReadings.value     = []
    valveClosed.value     = false
    blindsGasDone.value   = false
    const address = clientAddresses.value.find(a => a.id == addressId.value)
    try {
        const [devicesRes] = await Promise.all([
            axios.get(`/api/devices/${addressId.value}`),
            fetchClimate(address?.latitude, address?.longitude),
            fetchHeating(addressId.value),
            fetchOutlets(addressId.value),
            fetchGas(addressId.value),
        ])
        devices.value = devicesRes.data.data
    } finally {
        loading.value = false
    }
}

async function turnOffRiskyOutlet() {
    const reading = highRiskReading.value
    if (!reading) return
    containedOutlet.value = { ...reading }
    containedAt.value     = new Date()
    const r = outletReadings.value.find(r => r.device_id === reading.device_id)
    if (r) r.outlet_status = 'off'
    try {
        await axios.post('/api/commands', { device_id: reading.device_id, action: 'turn_off' })
    } catch { /* mantener estado contenido aunque falle el comando */ }
}

function acknowledgeRisk() {
    containedOutlet.value  = null
    containedAt.value      = null
    incidentModal.value    = false
    incidentLogModal.value = false
}

onMounted(() => {
    setInterval(() => { ticker.value = Date.now() }, 60000)
    setInterval(() => { if (addressId.value) fetchGas(addressId.value) }, 15000)
})

async function openAllBlinds() {
    blindsLoading.value = true
    const prev = blinds.value.map(d => ({ id: d.id, status: d.status }))
    blinds.value.forEach(d => { d.status = 'open' })
    try {
        await axios.patch(`/api/blinds/${addressId.value}/open-all`)
    } catch {
        prev.forEach(({ id, status }) => {
            const d = devices.value.find(d => d.id === id)
            if (d) d.status = status
        })
    } finally {
        blindsLoading.value = false
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
