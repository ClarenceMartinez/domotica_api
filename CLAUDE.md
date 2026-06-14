# Casa Inteligente — Domótica

Sistema de domótica escolar. Clientes gestionan inmuebles con dispositivos (luces, sensores, cámaras, persianas). Laravel sirve la API y las vistas Blade que montan Vue SFC. El Raspberry Pi en Python recibe comandos desde Laravel y los ejecuta en hardware físico.

---

## Comandos Esenciales

```bash
# Instalar dependencias
composer install
npm install

# Desarrollo
php artisan serve          # Backend en :8000
npm run dev                # Vite watch para Vue SFC

# Build producción
npm run build

# Base de datos
php artisan migrate
php artisan migrate:fresh --seed   # Reset + datos de prueba

# Tests
php artisan test
```

---

## Stack

| Capa | Tecnología |
|------|-----------|
| Backend | Laravel 11 + PHP 8.2 |
| Base de datos | MariaDB |
| Frontend | Blade + Vue 3 SFC (montados via Vite) |
| IoT | Python 3 en Raspberry Pi |
| HTTP Client (Vue) | Axios |

**Patrón Vue en Blade:** cada vista Blade incluye un `<div id="app-xxx">` donde se monta un componente Vue SFC independiente. Vite compila los SFC. No hay Vue Router global — la navegación la maneja Laravel.

```blade
{{-- resources/views/luces/index.blade.php --}}
<div id="app-luces" data-direccion-id="{{ $direccionId }}"></div>
@vite(['resources/js/apps/luces.js'])
```

```js
// resources/js/apps/luces.js
import { createApp } from 'vue'
import LucesPanel from '../components/LucesPanel.vue'

const el = document.getElementById('app-luces')
createApp(LucesPanel, { ...el.dataset }).mount(el)
```

---

## Estructura de Archivos

```
app/
  Http/Controllers/
    ClienteController.php
    DireccionController.php
    DispositivoController.php
    ComandoController.php        # Envía comandos al RPi
  Models/
    Cliente.php
    Direccion.php
    Plan.php
    Dispositivo.php
    ComandoLog.php

resources/
  views/                         # Blade (estructura y layout)
    clientes/
    direcciones/
    dashboard/
  js/
    apps/                        # Entry points Vue por vista
      luces.js
      persianas.js
      dashboard.js
    components/                  # Vue SFC
      LucesPanel.vue
      ToggleLuz.vue
      PersianasControl.vue
      ClimaWidget.vue

routes/
  web.php                        # Rutas Blade (Laravel)
  api.php                        # API REST para Vue + RPi

raspberry/                       # Proyecto Python separado
  main.py
  controladores/
    luces.py
    sensores.py
```

---

## Modelo de Datos

```
clientes
  └── direcciones  (latitud, longitud — ingreso manual fase 1)
        └── plan_id → planes
              └── dispositivos  (nombre personalizado, tipo, estado)
                    └── comandos_log
```

**Tipos de dispositivo:** `luz` | `persiana` | `sensor_puerta` | `sensor_ventana` | `camara` | `calefaccion`

---

## API REST (para Vue y Raspberry Pi)

```
GET    /api/dispositivos/{direccion}          # Lista dispositivos con estado
POST   /api/comandos                          # Enviar comando al RPi
  body: { dispositivo_id, accion }            # accion: encender|apagar|abrir|cerrar
GET    /api/clima/{direccion}                 # Clima externo por coordenadas
```

**Flujo de comando:**
```
Vue SFC → POST /api/comandos → Laravel → HTTP → Raspberry Pi Python → Hardware
                                       → guarda en comandos_log
```

---

## Fases de Desarrollo

### Fase 1 — MVP ✅ (implementar primero)
- [ ] CRUD clientes y direcciones
- [ ] Asignación de plan a dirección
- [ ] Configuración de dispositivos (nombrar)
- [ ] Panel de luces con toggle (Vue SFC → API → RPi)
- [ ] RPi recibe comandos y ejecuta GPIO

### Fase 2
- [ ] Control de persianas
- [ ] Widget de clima externo (API por coordenadas)
- [ ] Dashboard completo (consumo, temperatura, seguridad)
- [ ] Automatizaciones básicas (persianas por clima/hora)

### Fase 3
- [ ] Mapa interactivo para coordenadas
- [ ] Historial y logs de eventos
- [ ] Notificaciones
- [ ] App móvil

---

## Convenciones

- **Controladores:** un controlador por recurso, métodos REST estándar.
- **Vue SFC:** un archivo `.vue` por componente. Props desde `data-*` del Blade. Llamadas HTTP solo con Axios desde el componente.
- **Comandos al RPi:** siempre pasar por `ComandoController` — nunca llamar al RPi directamente desde otro controlador.
- **Estados de dispositivo:** guardar en tabla `dispositivos.estado` después de cada comando exitoso.
- **No modificar:** `raspberry/` desde el proyecto Laravel — es un repo/proyecto separado.

---

## Contexto del Proyecto

- Proyecto escolar con hardware físico (sensores de luz reales en RPi).
- La alumna conoce Python básico; es su primera app web completa.
- Prioridad MVP: que el toggle de luz en el browser prenda/apague la luz física.
- Latitud/longitud se copia y pega manualmente (sin mapa en fase 1).