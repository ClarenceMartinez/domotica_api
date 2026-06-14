import { createApp } from 'vue'
import LucesPanel from '../components/LucesPanel.vue'

const el = document.getElementById('app-luces')
if (el) {
    createApp(LucesPanel, { ...el.dataset }).mount(el)
}
