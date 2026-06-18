# Módulo Eléctrico (Smart Outlets)

## Objetivo

Implementar el módulo de monitoreo y gestión de tomas de corriente inteligentes (Smart Outlets).

La funcionalidad debe permitir:

- Recibir información de consumo eléctrico desde el Raspberry Pi.
- Registrar todas las lecturas en la base de datos.
- Detectar situaciones de riesgo eléctrico.
- Mostrar el estado de los dispositivos en el Dashboard.
- Permitir apagar un outlet cuando exista una condición de riesgo.
- Registrar y visualizar el historial de incidentes.

---

# Comunicación Raspberry Pi → Aplicación

El Raspberry Pi enviará periódicamente el estado de cada outlet mediante la API.

## Estado normal

```json
{
  "address_id": 1,
  "house_id": 1,
  "device": "PICO-W-01",
  "device_id": 8,
  "device_type": "smart_outlet",
  "outlet_name": "Bedroom outlet",
  "outlet_status": "on",
  "power_watts": 45,
  "minutes_on": 12,
  "minutes_over_limit": 0,
  "max_safe_minutes": 30,
  "appliance_type": "tv_or_medium_device",
  "energy_status": "normal",
  "risk_level": "normal",
  "message": "Consumo eléctrico normal"
}
```

---

## Estado de riesgo

```json
{
  "address_id": 1,
  "house_id": 1,
  "device": "PICO-W-01",
  "device_id": 8,
  "device_type": "smart_outlet",
  "outlet_name": "Bedroom outlet",
  "outlet_status": "off",
  "power_watts": 1200,
  "minutes_on": 31,
  "minutes_over_limit": 1,
  "max_safe_minutes": 30,
  "appliance_type": "high_power_appliance",
  "energy_status": "danger",
  "risk_level": "high",
  "message": "Bedroom outlet exceeded safe power usage time"
}
```

---

# Persistencia

Registrar cada lectura recibida desde el Raspberry Pi.

Se debe crear una tabla similar a las utilizadas para otros sensores (`heating_readings`, `blinds_readings`, etc.).

Cada registro deberá almacenar, como mínimo:

- address_id
- device_id
- outlet_name
- outlet_status
- power_watts
- minutes_on
- minutes_over_limit
- max_safe_minutes
- appliance_type
- energy_status
- risk_level
- message
- created_at

No se deben sobrescribir registros; cada lectura representa un evento histórico.

---

# Dashboard

Se debe agregar un nuevo placeholder correspondiente al módulo eléctrico.

El placeholder tendrá tres estados posibles:

1. Estado normal.
2. Riesgo detectado.
3. Riesgo contenido.

---

# Estado inicial (Normal)

Cuando no exista ningún riesgo eléctrico, el placeholder debe mostrar la lista de todos los Smart Outlets.

Cada elemento debe mostrar:

- Nombre asignado del outlet.
- Consumo actual (Watts).
- Corriente (Amperes).
- Estado (On / Off).
- Nivel de riesgo.

Ejemplo:

| Outlet | Power | Current | Status | Risk |
|---------|-------|---------|--------|------|
| Bedroom outlet | 45 W | 0.41 A | On | Normal |
| Kitchen outlet | 80 W | 0.73 A | On | Normal |

---

## Resumen general

Después de la lista se debe mostrar un resumen con:

- Potencia total (Watts).
- Corriente total (Amperes).
- Voltaje total.

### Voltaje

Para esta primera versión se puede considerar un voltaje fijo de **110 V**, ya que el sistema está pensado para Estados Unidos.

> En una versión futura el voltaje podrá provenir del sensor o de la configuración de la vivienda.

---

## Estado general del sistema

Debajo del resumen debe mostrarse una etiqueta indicando el nivel de riesgo general.

Ejemplo:

```
No electrical risk detected
```

Este corresponde al estado normal del placeholder.

---

# Estado: Riesgo detectado

Cuando cualquier outlet tenga un nivel de riesgo alto (`risk_level = high`), el placeholder cambia completamente su presentación.

Debe mostrar:

## Cabecera

- Icono de electricidad.
- Texto **ELECTRICAL SAFETY** en color rojo y negrita.
- Badge alineado a la derecha con el texto:

```
Alert
```

utilizando tonos rojos.

---

## Información principal

Mostrar el mensaje:

```
Risk detected
```

Con un subtítulo:

```
<Device Name> outlet exceeded safe power usage for X minutes.
```

Donde `X` corresponde al campo `minutes_over_limit`.

---

## Información del dispositivo

Mostrar tres columnas.

### Columna 1

- Icono del outlet.
- Texto pequeño:

```
Outlet
```

- Nombre del dispositivo.

---

### Columna 2

Título:

```
Power Usage
```

Debajo:

- Valor de `risk_level`.

---

### Columna 3

Título:

```
Time-over limit
```

Con un icono de reloj.

Debajo:

- Tiempo de encendido (`minutes_on`) resaltado en tonos rojos.

---

## Acción recomendada

Mostrar el mensaje:

```
Action recommended: Turn off outlet.
```

---

## Acciones disponibles

Mostrar dos botones apilados:

### Botón 1

```
Turn Off Outlet
```

Al presionarlo:

- El outlet cambia a estado `off`.
- Se registra la acción.
- El placeholder cambia al estado **Riesgo contenido**.

---

### Botón 2

```
View Device Details
```

Debe abrir un modal con toda la información recibida del dispositivo.

---

# Estado: Riesgo contenido

Después de apagar el outlet, el placeholder debe mostrar la última acción realizada.

Debe indicar:

- El outlet fue apagado.
- `Power State = Off`
- `Risk State = Contained`

Como pie del placeholder se debe mostrar:

```
X minutes ago
```

Indicando hace cuánto ocurrió la acción.

---

# Historial del incidente

Al hacer clic sobre el placeholder en estado **Riesgo contenido**, debe abrirse un modal con el detalle del incidente.

Debe listar las acciones realizadas.

Ejemplo:

1. `<Device Name>` outlet power disconnected.
2. Risk state changed to Contained.
3. Alert sent to homeowner.
4. Incident recorded in activity log.
5. Follow-up review recommended.

Después de la lista se debe mostrar:

```
Occurred X minutes ago
```

---

## Acciones del modal

Mostrar dos botones en una misma fila.

### Botón 1

```
Acknowledge
```

Función:

- Marca el incidente como leído.
- Cierra el flujo del incidente.
- El placeholder vuelve al estado inicial (Normal).

---

### Botón 2

```
View Incident Log
```

Debe abrir un segundo modal con el historial completo del incidente de riesgo alto.

---

# Placeholder de consumo eléctrico

En el Dashboard ya existe un placeholder para el consumo eléctrico.

Este componente deberá mostrar:

- Consumo instantáneo total (Watts).
- Consumo acumulado del día.

Los valores deben actualizarse con la información recibida desde los Smart Outlets.

---

# Consideraciones técnicas

- El amperaje debe calcularse utilizando la fórmula:

```
Current (A) = Power (W) / Voltage (V)
```

- Para esta versión el voltaje será fijo en **110 V**.

- Todos los incidentes deben registrarse en la base de datos para mantener un historial.

- El diseño debe permitir soportar múltiples Smart Outlets por vivienda.

---

# Alcance

## Incluido

- Recepción de datos desde Raspberry Pi.
- Registro histórico de lecturas.
- Detección de riesgo eléctrico.
- Dashboard con listado de Smart Outlets.
- Resumen de consumo total.
- Estado general de riesgo.
- Flujo completo de alerta.
- Apagado remoto del outlet.
- Registro de incidentes.
- Modal de detalle del dispositivo.
- Modal de historial del incidente.
- Actualización del placeholder de consumo eléctrico.

---

## No incluido

- Configuración dinámica del voltaje.
- Umbrales configurables por usuario.
- Notificaciones Push, SMS o correo electrónico.
- Automatizaciones basadas en reglas.
- Estadísticas históricas de consumo.