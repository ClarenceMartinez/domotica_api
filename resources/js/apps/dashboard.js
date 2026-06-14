import { createApp } from 'vue'
import DashboardPanel from '../components/DashboardPanel.vue'

const el = document.getElementById('app-dashboard')
if (el) {
    const clientes = JSON.parse(el.dataset.clientes ?? '[]')
    createApp(DashboardPanel, { clientes }).mount(el)
}
