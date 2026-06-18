# Gestión de Persianas (Blinds)

## Objetivo

Implementar el soporte para persianas (Blinds) dentro de la aplicación de domótica.

La funcionalidad debe permitir:

- Recibir el estado actual de las persianas desde el Raspberry Pi.
- Almacenar las lecturas en la base de datos.
- Permitir controlar las persianas desde la aplicación.
- Mostrar el estado actual en el Dashboard.

---

# Modelo de datos

Las persianas **no utilizan un estado booleano (`open` / `close`)**.

Cada persiana almacena un porcentaje de apertura.

| Valor | Estado |
|--------|--------|
| 0 | Cerrada |
| 100 | Abierta |

> **Nota:** Aunque el modelo soporta valores entre `0` y `100`, en esta primera versión solo se utilizarán los valores `0` y `100`.

---

# Comunicación Raspberry Pi → Aplicación

El Raspberry Pi enviará un payload con la siguiente estructura:

```json
{
    "device_id": 1,
    "house_id": 5,
    "value": 100
}
```

### Campos

- `device_id`
    - Identificador del dispositivo.

- `house_id`
    - Corresponde al campo `address_id` dentro de la aplicación.

- `value`
    - Nivel de apertura de la persiana (`0-100`).

---

# Persistencia

Crear una tabla similar a `heating_readings`.

Debe almacenar al menos:

- `device_id`
- `address_id`
- `value`
- `created_at`

Cada lectura enviada por el Raspberry Pi debe registrarse como un nuevo registro histórico.

---

# Control desde la aplicación

La aplicación debe permitir modificar el valor de apertura de una persiana.

Cuando el usuario cambie el estado:

- Se debe actualizar el valor correspondiente para que el Raspberry Pi lo lea posteriormente.

En esta versión únicamente se enviarán los valores:

- `0` → Cerrar persiana.
- `100` → Abrir persiana.

---

# Dashboard

Actualmente existe un placeholder destinado a las persianas.

Debe implementarse la siguiente funcionalidad.

## Estado general

Mostrar el estado actual de las persianas.

Ejemplos:

```
Persianas: Abiertas
```

```
Persianas: Cerradas
```

En futuras versiones podrá contemplarse un estado mixto.

---

## Botón: Abrir todas

Agregar un botón:

```
Abrir todas las persianas
```

Al presionarlo:

- Todas las persianas deben actualizarse con el valor `100`.

---

## Botón: Administrar persianas

Agregar un segundo botón:

```
Administrar persianas
```

Este botón abrirá un modal para la gestión individual.

---

# Modal de administración

El modal debe listar todas las persianas pertenecientes a la vivienda.

Cada elemento debe mostrar:

- Nombre de la persiana.
- Estado actual.
- Botón de acción.

Ejemplo:

```
Sala
Estado: Cerrada
[ Abrir ]

Dormitorio
Estado: Abierta
[ Cerrar ]

Cocina
Estado: Cerrada
[ Abrir ]
```

Las acciones disponibles serán:

- **Abrir** → establece el valor `100`.
- **Cerrar** → establece el valor `0`.

---

# Consideraciones

- Mantener compatibilidad futura con valores intermedios (`1-99`).
- La interfaz de esta primera versión únicamente permitirá trabajar con `0` y `100`.
- La estructura de persistencia debe seguir el mismo patrón utilizado por `heating_readings`.

---

# Alcance

## Incluido

- Recepción de datos desde el Raspberry Pi.
- Persistencia de lecturas en base de datos.
- Actualización del valor desde la aplicación.
- Visualización del estado en el Dashboard.
- Botón para abrir todas las persianas.
- Modal de administración individual.
- Soporte para valores `0` y `100`.

## No incluido

- Control mediante porcentaje.
- Slider de apertura.
- Estados intermedios (`1-99`).
- Automatizaciones relacionadas con persianas.