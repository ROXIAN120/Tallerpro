# Manual y Flujo Operativo del Sistema "TallerPro"

Este documento describe el flujo completo de principio a fin sobre cómo se debe utilizar el sistema del taller automotriz, desde que ingresa un vehículo hasta que se entrega y se analiza la rentabilidad.

---

## 1. Inicio de Sesión (Administrador)
- **Ruta:** `http://127.0.0.1:8000/`
- **Credenciales:** 
  - **Email:** `admin@admin.com`
  - **Password:** `12345678`
- **Acción:** Al ingresar, el sistema redirige automáticamente al **Dashboard Gerencial**, donde se observan los KPIs principales (Órdenes activas, Vehículos entregados en el mes, Alertas de stock crítico).

---

## 2. Recepción del Vehículo (Ingreso al Taller)
*(Nota: Actualmente las órdenes de trabajo pueden crearse por base de datos o a través del controlador correspondiente que se integraría en el futuro. Para efectos de prueba, se asume la creación de una Orden de Trabajo).*

Cuando un cliente llega al taller:
1. Se registra la información del Cliente (Nombre, Teléfono) y del Vehículo (Placa, Marca, Modelo).
2. Se genera una nueva **Orden de Trabajo (OT)** en estado **"PENDIENTE"**.
3. Se asigna a un mecánico y se describe el servicio a realizar (Ej: "Mantenimiento General").

---

## 3. Trabajo de los Mecánicos (Módulo Kanban)
- **Ruta:** `Menú Lateral -> Kanban` (`/taller/kanban`)
- **Flujo:**
  1. **Pendientes:** El mecánico observa su tablero y ve el vehículo recién ingresado en la columna gris "Pendientes".
  2. **En Proceso:** Al momento de empezar a trabajar en el auto, el mecánico hace clic en **"Iniciar"**. 
     - *Acción automática:* El sistema mueve la tarjeta a la columna "En Proceso" y **registra la hora exacta de inicio** para medir la productividad.
  3. **Finalizados:** Cuando la reparación termina, el mecánico hace clic en **"Finalizar"**.
     - *Acción automática:* La tarjeta pasa a "Finalizados" y se sella la **hora de fin**.

---

## 4. Seguimiento Público por parte del Cliente (Portal B2C)
- **Ruta:** `http://127.0.0.1:8000/seguimiento` (Accesible desde cualquier celular sin necesidad de usuario/contraseña).
- **Flujo:**
  1. El cliente, desde su casa, ingresa la **Placa** de su vehículo (Ej: `ABC-1234`).
  2. El sistema consulta en tiempo real el Módulo Kanban.
  3. Muestra una interfaz gráfica indicando si el auto sigue en espera, si ya lo están reparando, o si ya está listo para ser recogido.

---

## 5. Gestión de Repuestos (Módulo de Inventario)
- **Ruta:** `Menú Lateral -> Precios` y `Menú Lateral -> Kardex`
- **Flujo de Precios (`/inventario/precios`):**
  - El administrador puede ver todo el stock. Si un proveedor sube el costo de una pieza, el admin edita el **Costo** y el **Margen de Ganancia (%)**. 
  - El sistema **calcula dinámicamente** el nuevo Precio de Venta.
- **Flujo de Trazabilidad (`/inventario/kardex`):**
  - Todo repuesto utilizado en las reparaciones se registra aquí.
  - Se pueden hacer registros manuales mediante el botón **"Nuevo Movimiento"**. Si se compran 10 filtros de aceite, se registra una "ENTRADA" por "Compra a proveedor", sumando al stock automáticamente.

---

## 6. Cierre de Mes (Dashboard Financiero)
- **Ruta:** `Menú Lateral -> Finanzas` (`/reportes/dashboard`)
- **Flujo:**
  1. Al final de la semana o mes, el administrador revisa este panel.
  2. El sistema cruza los datos de:
     - **Ingresos:** Cobros de las órdenes que llegaron a estado "Finalizado".
     - **Costos:** El costo base de los repuestos consumidos en el Kardex.
  3. Calcula automáticamente la **Utilidad Neta** y el **Margen Global** del taller.
  4. **Exportación a Excel:** Con un solo clic en "Exportar a Excel", el administrador descarga un reporte `.xlsx` formateado, listo para entregarlo al contador de la empresa.

---

*Fin del ciclo operativo.*
