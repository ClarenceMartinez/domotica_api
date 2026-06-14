import { createApp } from 'vue'
import DashboardPanel from '../components/DashboardPanel.vue'

const el = document.getElementById('app-dashboard')
if (el) {
    const clients = JSON.parse(el.dataset.clients ?? '[]')
    createApp(DashboardPanel, { clients }).mount(el)
}
