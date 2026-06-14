import { createApp } from 'vue'
import DevicesPanel from '../components/DevicesPanel.vue'

const el = document.getElementById('app-devices')
if (el) {
    createApp(DevicesPanel, { ...el.dataset }).mount(el)
}
