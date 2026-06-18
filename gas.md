# Módulo de Gas

## Objetivo

Implementar el módulo de monitoreo de fugas de gas, permitiendo:

- Registrar todas las lecturas enviadas por el sensor de gas conectado al Raspberry Pi.
- Mostrar el estado actual de los sensores en el dashboard.
- Detectar automáticamente situaciones de riesgo.
- Ejecutar acciones de emergencia de forma automática.
- Permitir al usuario ejecutar acciones manuales desde la interfaz.

---

# Comunicación con el Raspberry Pi

El Raspberry Pi enviará lecturas mediante la API.

## Lectura normal

```json
{
  "address_id": 1,
  "house_id": 1,
  "device": "PICO-W-01",
  "device_id": 8,
  "sensor": "MQ2",
  "gas_value": 12000,
  "gas_status": "normal",
  "message": "Gas normal"
}
```

## Lectura con riesgo

```json
{
  "address_id": 1,
  "house_id": 1,
  "device": "PICO-W-01",
  "device_id": 8,
  "sensor": "MQ2",
  "gas_value": 34500,
  "gas_status": "danger",
  "message": "Posible fuga de gas detectada"
}
```

---

# Backend

## Registro de lecturas

Cada lectura recibida desde el Raspberry Pi debe almacenarse en la base de datos.

La información mínima a guardar es:

- address_id
- house_id
- device_id
- sensor
- gas_value
- gas_status
- message
- timestamp

La estructura puede seguir el mismo patrón utilizado en otros módulos de lecturas (por ejemplo `heating_readings`).

---

# Dashboard

Agregar un nuevo placeholder ubicado al lado del módulo de electricidad.

---

# Estado inicial (Normal)

Cuando todos los sensores reporten estado **normal**, el placeholder debe mostrar:

## Lista de sensores

Cada sensor debe mostrar:

- Nombre asignado
- Estado actual
- Tiempo desde la última lectura

Ejemplo:

```
Kitchen Sensor
Status: Normal
Last reading: 2 minutes ago
```

## Estado general

Debajo de la lista debe mostrarse un indicador similar a:

```
No gas leak detected
```

Este será el estado por defecto del componente.

---

# Estado de emergencia

Cuando cualquier sensor reporte:

```
gas_status = danger
```

el placeholder debe cambiar completamente.

## Encabezado

Mostrar:

- Texto **GAS LEAK** en color rojo y negrita.
- Icono de fuego o llama.

---

## Información del evento

Mostrar:

1. State: Danger
2. Sensor: Device Name
3. Gas value: X ppm
4. Last reading: X minutes ago

---

## Alerta

Mostrar un componente Alert con:

Título:

```
Gas leak detected
```

Subtexto:

```
Required immediate action!
```

Botón:

```
Emergency shutdown
```

Al hacer clic debe abrir un modal.

---

# Modal: Emergency Shutdown

## Título

```
Emergency Shutdown
```

---

## Card de riesgo

Debe contener:

- Icono de alerta
- Título:

```
Gas leak detected
```

En una fila mostrar:

- Risk level: Danger
- Sensor: Device Name
- Last reading: X minutes ago

---

# Acciones recomendadas

Mostrar una lista de acciones.

---

## 1. Close main gas valve

Botón:

```
Close valve
```

Acción:

- Registrar el comando en el sistema.
- El Raspberry Pi leerá este comando y ejecutará el cierre de la válvula principal.

---

## 2. Shutdown heating (Automático)

Cuando la aplicación reciba una alerta de riesgo:

```
gas_status = danger
```

Debe ejecutar inmediatamente el endpoint existente para apagar toda la calefacción.

Debe apagar todos los dispositivos de calefacción asociados a la dirección (`address_id`).

No requiere intervención del usuario.

---

## 3. Shutdown smart outlets (Automático)

Cuando se detecte una fuga de gas, la aplicación también debe ejecutar inmediatamente el endpoint existente para apagar todos los **Smart Outlets** asociados a la dirección (`address_id`).

Objetivo:

- Evitar chispas.
- Reducir el riesgo de incendio o explosión.

No requiere intervención del usuario.

---

## 4. Open windows / blinds

Botón:

```
Open
```

Acción:

Invocar los endpoints existentes para ordenar al Raspberry Pi abrir:

- Ventanas motorizadas.
- Persianas motorizadas.

Debe aplicarse a todos los dispositivos asociados a la dirección (`address_id`).

---

## 5. Activate alarm

Mostrar:

```
Alarm activated
```

La alarma debe considerarse activada durante el estado de emergencia.

---

## 6. Notify contacts

Botón:

```
Notify contacts
```

Por el momento:

- Dejar preparado el método.
- No implementar la lógica de notificación.

Los endpoints aún no existen.

---

## 7. Call emergency services

Botón:

```
Call 112
```

Al presionar:

- Iniciar una llamada telefónica al número **112**.

---

# Card de evacuación

Debajo de las acciones mostrar una segunda Card.

## Contenido

Icono de alerta

Título:

```
Evacuate the house immediately
```

Subtítulo:

```
Don't turn on any lights or outlets.
```

### What to do now

1. Evacuate the house with all members.
2. Don't use cell phone inside the house.
3. Wait for emergency services to arrive outside the house.

---

## Mensaje final

Mostrar texto en estilo muted:

```
Stay in a safe location. The emergency services has been notified.
```

---

# Recuperación del estado

Cuando el Raspberry Pi vuelva a enviar una lectura con:

```
gas_status = normal
```

el sistema debe:

- Finalizar el estado de emergencia.
- Restaurar el placeholder a su estado inicial.
- Mostrar nuevamente la lista de sensores.
- Mostrar el mensaje:

```
No gas leak detected
```

- Continuar mostrando la última lectura registrada para cada sensor.

---

# Comportamiento automático requerido

Cuando se detecte una fuga (`gas_status = danger`), la aplicación debe ejecutar inmediatamente las siguientes acciones sin esperar interacción del usuario:

- Apagar todos los dispositivos de calefacción mediante el endpoint existente.
- Apagar todos los Smart Outlets mediante el endpoint existente.
- Mantener el estado de emergencia activo hasta recibir una nueva lectura con `gas_status = normal`.

Las siguientes acciones permanecerán disponibles para ejecución manual desde el modal:

- Cerrar la válvula principal de gas.
- Abrir ventanas/persianas.
- Notificar contactos (placeholder).
- Llamar al 112.