# PROYECTO FINAL – PROGRAMACIÓN WEB II

## Sistema de Gestión Integral para Taller Automotriz "TallerPro"

---

**Universidad:** [Nombre de la Universidad]
**Facultad:** [Nombre de la Facultad]
**Carrera:** Ingeniería de Sistemas / Ingeniería Informática
**Asignatura:** Programación Web II
**Docente:** [Nombre del Docente]
**Integrantes:** [Nombre de los integrantes del grupo]
**Gestión:** 2026
**Fecha:** 29 de julio de 2026

---

## HOJA DE CONTROL DOCUMENTAL

| Versión | Fecha       | Descripción del cambio              | Responsable       |
|---------|-------------|--------------------------------------|--------------------|
| 1.0     | 29/07/2026  | Creación inicial del documento       | [Nombre]           |
| 1.1     | [Fecha]     | Revisión y correcciones finales      | [Nombre]           |

---

## ÍNDICE GENERAL

*(Generar automáticamente en Word: Referencias → Tabla de Contenido)*

## ÍNDICE DE FIGURAS

*(Generar automáticamente en Word: Referencias → Insertar Tabla de Ilustraciones)*

## ÍNDICE DE TABLAS

*(Generar automáticamente en Word: Referencias → Insertar Tabla de Ilustraciones → Tabla)*

---

# CAPÍTULO I. INTRODUCCIÓN

## 1.1 Antecedentes

En Bolivia, la industria automotriz ha experimentado un crecimiento sostenido en los últimos años. Según el Instituto Nacional de Estadística (INE), el parque automotor boliviano supera los 2.5 millones de unidades registradas. Este crecimiento genera una demanda creciente de servicios de mantenimiento y reparación vehicular. Sin embargo, la mayoría de los talleres mecánicos en el país operan de manera informal, registrando sus operaciones en cuadernos físicos, hojas de cálculo o, en el peor de los casos, de memoria. Esta situación provoca pérdida de información, descontrol del inventario de repuestos, imposibilidad de dar seguimiento al estado de los vehículos y una gestión financiera deficiente.

## 1.2 Planteamiento del Problema

Los talleres mecánicos enfrentan múltiples desafíos operativos: no tienen un sistema centralizado para registrar órdenes de trabajo, no pueden controlar el stock de repuestos en tiempo real, los clientes no tienen forma de consultar el estado de su vehículo sin llamar por teléfono, y la generación de reportes financieros es un proceso manual y propenso a errores. Estos problemas resultan en pérdida de clientes, desperdicio de recursos y una rentabilidad inferior a la esperada.

## 1.3 Justificación

### Justificación Técnica
El proyecto aplica tecnologías modernas de desarrollo web como Laravel 13 (PHP), React.js, Inertia.js, MySQL y Docker, cumpliendo con estándares de la industria en arquitectura MVC, patrón de diseño Service Layer y despliegue contenerizado.

### Justificación Social
El sistema beneficia directamente a los propietarios de talleres mecánicos al digitalizar sus operaciones, reduciendo tiempos de atención y mejorando la experiencia del cliente con un portal de seguimiento público.

### Justificación Académica
El proyecto integra los conocimientos adquiridos durante la asignatura de Programación Web II: desarrollo backend con Laravel, frontend reactivo con React, integración con bases de datos relacionales, contenerización con Docker y despliegue en la nube.

## 1.4 Objetivo General

Desarrollar un sistema web integral para la gestión de talleres automotrices que permita administrar órdenes de trabajo, inventario de repuestos, catálogo de servicios, seguimiento del cliente y reportes financieros, utilizando Laravel como framework principal, contenerizado con Docker y desplegado en un hosting público.

## 1.5 Objetivos Específicos

1. Implementar un módulo de autenticación con roles de usuario (administrador).
2. Desarrollar un tablero Kanban para la gestión visual de órdenes de trabajo (Pendiente → En Proceso → Finalizado).
3. Crear un módulo de inventario con control de stock mediante sistema Kardex (entradas y salidas).
4. Implementar un catálogo de servicios y repuestos con gestión de precios y márgenes de ganancia.
5. Desarrollar un portal público de seguimiento para que los clientes consulten el estado de su vehículo por número de placa.
6. Generar reportes financieros dinámicos con filtros por rango de fechas y exportación a Excel (CSV).
7. Implementar la generación de facturas en formato PDF.
8. Contenerizar la aplicación con Docker y desplegarla en un hosting accesible desde Internet.

## 1.6 Alcance

El sistema cubre los siguientes módulos funcionales:
- **Dashboard Gerencial:** Panel de estadísticas generales del taller.
- **Kanban Operativo:** Gestión visual del flujo de trabajo con estados.
- **Catálogo de Repuestos:** CRUD completo con gestión de proveedores y categorías.
- **Catálogo de Servicios:** CRUD completo con categorías y precios base.
- **Kardex de Inventario:** Registro de movimientos de entrada/salida con trazabilidad.
- **Gestión de Precios:** Configuración de costos y márgenes de ganancia por repuesto.
- **Órdenes de Trabajo:** Creación, edición, asignación de servicios y repuestos, finalización.
- **Portal del Cliente:** Seguimiento público del estado del vehículo por placa.
- **Reportes Financieros:** Dashboard con KPIs, filtros por fecha y exportación CSV.
- **Facturación PDF:** Generación de facturas profesionales por orden finalizada.

## 1.7 Limitaciones

- El sistema no implementa un módulo de nómina o recursos humanos para los mecánicos.
- No se implementa un sistema de notificaciones por correo electrónico o SMS.
- El módulo de pagos está diseñado pero no incluye integración con pasarelas de pago en línea.
- La aplicación móvil nativa queda fuera del alcance (aunque el sistema es responsive).

## 1.8 Eje Transversal y ODS

El proyecto se alinea con los siguientes Objetivos de Desarrollo Sostenible (ODS) de las Naciones Unidas:

- **ODS 8 – Trabajo Decente y Crecimiento Económico:** Al digitalizar las operaciones de talleres mecánicos, se promueve la formalización de pequeñas empresas, se mejoran sus procesos productivos y se incrementa su competitividad económica.
- **ODS 9 – Industria, Innovación e Infraestructura:** El sistema introduce innovación tecnológica en un sector tradicionalmente manual, modernizando la infraestructura digital de los talleres automotrices.
- **ODS 12 – Producción y Consumo Responsables:** El módulo Kardex permite un control preciso del inventario de repuestos, reduciendo el desperdicio y optimizando el uso de recursos materiales.

---

# CAPÍTULO II. INGENIERÍA DE REQUERIMIENTOS

## 2.1 Requerimientos Funcionales

| ID    | Requerimiento                                         | Prioridad |
|-------|-------------------------------------------------------|-----------|
| RF-01 | El sistema debe permitir el inicio de sesión con credenciales (email y contraseña). | Alta |
| RF-02 | El sistema debe mostrar un Dashboard con estadísticas generales del taller. | Alta |
| RF-03 | El sistema debe permitir crear nuevas Órdenes de Trabajo registrando cliente, vehículo y diagnóstico. | Alta |
| RF-04 | El sistema debe gestionar las órdenes en un tablero Kanban con los estados: Pendiente, En Proceso y Finalizado. | Alta |
| RF-05 | El sistema debe permitir agregar servicios y repuestos a una Orden de Trabajo. | Alta |
| RF-06 | El sistema debe descontar automáticamente el stock del inventario al agregar un repuesto a una orden. | Alta |
| RF-07 | El sistema debe devolver el stock al inventario si se elimina un repuesto de una orden. | Alta |
| RF-08 | El sistema debe permitir el registro de productos (repuestos) con sus categorías y proveedores. | Alta |
| RF-09 | El sistema debe permitir la gestión de servicios (mano de obra) con categorías y precios base. | Alta |
| RF-10 | El sistema debe implementar un Kardex para registrar movimientos de entrada y salida de inventario. | Alta |
| RF-11 | El sistema debe permitir configurar el costo y margen de ganancia de cada repuesto. | Media |
| RF-12 | El sistema debe generar reportes financieros con filtros por rango de fechas. | Alta |
| RF-13 | El sistema debe permitir exportar reportes a formato CSV compatible con Excel. | Media |
| RF-14 | El sistema debe generar facturas en formato PDF por cada orden finalizada. | Alta |
| RF-15 | El sistema debe ofrecer un portal público de seguimiento donde el cliente pueda consultar el estado de su vehículo por número de placa. | Alta |
| RF-16 | El sistema debe soportar modo oscuro y modo claro en toda la interfaz. | Baja |

## 2.2 Requerimientos No Funcionales

| ID     | Requerimiento                                                                   |
|--------|---------------------------------------------------------------------------------|
| RNF-01 | El sistema debe ser desarrollado con el framework Laravel versión 13.           |
| RNF-02 | El frontend debe utilizar React.js con Inertia.js para una experiencia SPA.     |
| RNF-03 | La base de datos debe ser MySQL 8.0.                                            |
| RNF-04 | El sistema debe ser contenerizado con Docker (Dockerfile + compose.yaml).       |
| RNF-05 | La interfaz debe ser responsive y adaptarse a dispositivos móviles y de escritorio. |
| RNF-06 | El tiempo de respuesta de cualquier página no debe exceder los 3 segundos.      |
| RNF-07 | El sistema debe estar desplegado y accesible desde una URL pública en Internet. |
| RNF-08 | El código fuente debe estar versionado en un repositorio de GitHub.             |

## 2.3 Reglas de Negocio

| ID    | Regla de Negocio                                                                              |
|-------|-----------------------------------------------------------------------------------------------|
| RN-01 | No se puede agregar un repuesto a una orden si el stock disponible es insuficiente.            |
| RN-02 | Al eliminar un repuesto de una orden, el stock debe ser devuelto automáticamente al Kardex.    |
| RN-03 | Solo las órdenes con estado "FINALIZADO" o "ENTREGADO" se contabilizan en los reportes financieros. |
| RN-04 | El margen de ganancia se calcula como: Precio Venta = Costo + (Costo x Margen%).              |
| RN-05 | La utilidad neta se calcula como: Ingresos Totales - Costos Operativos (costo de repuestos).  |
| RN-06 | El tablero Kanban solo muestra las órdenes finalizadas de las últimas 48 horas para optimizar rendimiento. |
| RN-07 | Un servicio no puede ser eliminado de una orden si tiene repuestos asociados.                  |

## 2.4 Restricciones del Sistema

- El sistema requiere PHP 8.3 o superior.
- Se requiere Node.js para la compilación de los assets del frontend (React/Vite).
- Se requiere MySQL 8.0 como motor de base de datos.
- El despliegue se realiza mediante Docker.

---

# CAPÍTULO III. MODELADO UML

## 3.1 Diagrama de Casos de Uso

> **[INSERTAR IMAGEN: Diagrama_Casos_Uso.png]**
> Crear este diagrama con herramientas como draw.io, Lucidchart o StarUML.
> Debe incluir los actores (Administrador y Cliente) y los casos de uso principales.

**Actores del Sistema:**
- **Administrador:** Tiene acceso completo al sistema.
- **Cliente (Público):** Accede únicamente al portal de seguimiento.

### Descripción de Casos de Uso Principales

**CU-01: Iniciar Sesión**

| Campo               | Descripción                                                |
|----------------------|------------------------------------------------------------|
| Actor                | Administrador                                              |
| Precondición         | El usuario debe estar registrado en el sistema.            |
| Flujo Principal      | 1. El usuario ingresa email y contraseña. 2. El sistema valida las credenciales. 3. Se redirige al Dashboard. |
| Postcondición        | El usuario accede al panel de administración.              |

**CU-02: Crear Orden de Trabajo**

| Campo               | Descripción                                                |
|----------------------|------------------------------------------------------------|
| Actor                | Administrador                                              |
| Precondición         | El usuario debe estar autenticado.                         |
| Flujo Principal      | 1. El usuario llena el formulario con datos del cliente, vehículo y diagnóstico. 2. El sistema crea automáticamente el cliente y vehículo si no existen. 3. Se crea la orden con estado "PENDIENTE". |
| Postcondición        | La orden aparece en la columna "Pendientes" del Kanban.    |

**CU-03: Gestionar Orden de Trabajo (Agregar Servicios/Repuestos)**

| Campo               | Descripción                                                |
|----------------------|------------------------------------------------------------|
| Actor                | Administrador                                              |
| Precondición         | La orden debe existir y estar en estado "EN PROCESO".      |
| Flujo Principal      | 1. Se selecciona un servicio del catálogo y se agrega. 2. Se selecciona un repuesto del stock disponible y se indica la cantidad. 3. El sistema descuenta automáticamente del inventario. |
| Postcondición        | La orden tiene servicios y repuestos asignados.            |

**CU-04: Consultar Estado del Vehículo (Portal Cliente)**

| Campo               | Descripción                                                |
|----------------------|------------------------------------------------------------|
| Actor                | Cliente (Público)                                          |
| Precondición         | El vehículo debe estar registrado en el sistema.           |
| Flujo Principal      | 1. El cliente ingresa su número de placa. 2. El sistema muestra el estado actual de la orden, servicios realizados y repuestos utilizados. |
| Postcondición        | El cliente visualiza el estado de su vehículo.             |

## 3.2 Diagrama de Clases

> **[INSERTAR IMAGEN: Diagrama_Clases.png]**
> Crear este diagrama usando draw.io o StarUML.
> Debe reflejar las 15 clases del modelo y sus relaciones.

**Explicación del Diagrama de Clases:**

El sistema cuenta con 15 modelos Eloquent, organizados en capas:

1. **Capa de Seguridad:** User, Role, Permiso
2. **Capa Comercial:** Cliente, Vehiculo, MarcaVehiculo, ModeloVehiculo
3. **Capa de Catálogo:** Servicio, CategoriaServicio, Repuesto, CategoriaRepuesto, Proveedor
4. **Capa de Inventario:** Inventario, MovimientoInventario
5. **Capa Operativa:** OrdenTrabajo, DetalleOrdenTrabajo, Sucursal

Relaciones principales:
- Un Cliente tiene muchos Vehiculos (1:N).
- Una OrdenTrabajo pertenece a un Cliente, un Vehiculo y una Sucursal.
- Una OrdenTrabajo tiene muchos DetalleOrdenTrabajo (1:N).
- Un DetalleOrdenTrabajo tiene relación N:M con Repuesto (tabla pivot detalle_repuesto).
- Un Repuesto tiene un Inventario (1:1), y cada Inventario tiene muchos MovimientoInventario.

---

# CAPÍTULO IV. BASE DE DATOS

## 4.1 Modelo Relacional

> **[INSERTAR IMAGEN: Modelo_ER.png]**
> Exportar el diagrama ER desde MySQL Workbench, DBeaver o phpMyAdmin (pestaña "Diseñador").

## 4.2 Diccionario de Datos

### Tabla: users

| Campo      | Tipo           | Restricción          | Descripción                       |
|------------|----------------|----------------------|-----------------------------------|
| id         | BIGINT UNSIGNED| PK, Auto Increment   | Identificador único del usuario   |
| name       | VARCHAR(255)   | NOT NULL             | Nombre completo                   |
| email      | VARCHAR(255)   | NOT NULL, UNIQUE     | Correo electrónico                |
| password   | VARCHAR(255)   | NOT NULL             | Contraseña cifrada con bcrypt     |
| rol_id     | BIGINT UNSIGNED| FK -> roles.id, NULL | Rol asignado al usuario           |
| estado     | BOOLEAN        | DEFAULT true         | Estado activo/inactivo            |

### Tabla: clientes

| Campo           | Tipo           | Restricción          | Descripción                      |
|-----------------|----------------|----------------------|----------------------------------|
| id              | BIGINT UNSIGNED| PK, Auto Increment   | Identificador único del cliente  |
| nombreCompleto  | VARCHAR(150)   | NOT NULL             | Nombre completo del cliente      |
| ci              | VARCHAR(20)    | NOT NULL, UNIQUE     | Cédula de identidad              |
| telefono        | VARCHAR(20)    | NULL                 | Número de teléfono               |
| direccion       | VARCHAR(255)   | NULL                 | Dirección domiciliaria           |

### Tabla: vehiculos

| Campo              | Tipo           | Restricción                  | Descripción                    |
|--------------------|----------------|------------------------------|--------------------------------|
| id                 | BIGINT UNSIGNED| PK, Auto Increment           | Identificador único            |
| cliente_id         | BIGINT UNSIGNED| FK -> clientes.id, CASCADE   | Cliente propietario            |
| modelo_vehiculo_id | BIGINT UNSIGNED| FK -> modelos_vehiculos.id   | Modelo del vehículo            |
| placa              | VARCHAR(15)    | NOT NULL, UNIQUE             | Número de placa                |
| color              | VARCHAR(30)    | NOT NULL                     | Color del vehículo             |
| anio               | INTEGER        | NOT NULL                     | Año de fabricación             |
| chasisVIN          | VARCHAR(50)    | UNIQUE, NULL                 | Número de chasis VIN           |

### Tabla: ordenes_trabajo

| Campo                | Tipo           | Restricción                  | Descripción                     |
|----------------------|----------------|------------------------------|---------------------------------|
| id                   | BIGINT UNSIGNED| PK, Auto Increment           | Identificador de la orden       |
| vehiculo_id          | BIGINT UNSIGNED| FK -> vehiculos.id           | Vehículo asociado               |
| cliente_id           | BIGINT UNSIGNED| FK -> clientes.id            | Cliente asociado                |
| sucursal_id          | BIGINT UNSIGNED| FK -> sucursales.id          | Sucursal donde se atiende       |
| fechaIngreso         | DATETIME       | NOT NULL                     | Fecha y hora de ingreso         |
| estado               | ENUM           | DEFAULT 'PENDIENTE'          | Estado de la orden              |
| diagnostico          | TEXT           | NULL                         | Diagnóstico del vehículo        |
| hora_inicio          | DATETIME       | NULL                         | Hora de inicio de reparación    |
| hora_fin             | DATETIME       | NULL                         | Hora de finalización            |

### Tabla: repuestos

| Campo                | Tipo           | Restricción                  | Descripción                     |
|----------------------|----------------|------------------------------|---------------------------------|
| id                   | BIGINT UNSIGNED| PK, Auto Increment           | Identificador del repuesto      |
| categoria_repuesto_id| BIGINT UNSIGNED| FK -> categorias_repuestos.id| Categoría del repuesto          |
| proveedor_id         | BIGINT UNSIGNED| FK -> proveedores.id         | Proveedor del repuesto          |
| nombre               | VARCHAR(150)   | NOT NULL                     | Nombre del repuesto             |
| precioUnitario       | DECIMAL(10,2)  | NOT NULL                     | Precio unitario original        |
| costo                | DECIMAL(10,2)  | DEFAULT 0                    | Costo de compra                 |
| margen_ganancia      | DECIMAL(5,2)   | DEFAULT 0                    | Margen de ganancia (%)          |

### Tabla: inventarios

| Campo        | Tipo           | Restricción                  | Descripción                     |
|--------------|----------------|------------------------------|---------------------------------|
| id           | BIGINT UNSIGNED| PK, Auto Increment           | Identificador del inventario    |
| repuesto_id  | BIGINT UNSIGNED| FK -> repuestos.id, UNIQUE   | Repuesto asociado (relación 1:1)|
| stockActual  | INTEGER        | DEFAULT 0                    | Stock actual disponible         |
| stockMinimo  | INTEGER        | DEFAULT 5                    | Stock mínimo de alerta          |

### Tabla: movimientos_inventario

| Campo           | Tipo           | Restricción                  | Descripción                     |
|-----------------|----------------|------------------------------|---------------------------------|
| id              | BIGINT UNSIGNED| PK, Auto Increment           | Identificador del movimiento    |
| inventario_id   | BIGINT UNSIGNED| FK -> inventarios.id         | Inventario asociado             |
| tipoMovimiento  | ENUM           | NOT NULL                     | Tipo: ENTRADA o SALIDA          |
| cantidad        | INTEGER        | NOT NULL                     | Cantidad del movimiento         |
| fecha           | DATE           | NOT NULL                     | Fecha del movimiento            |

### Tabla: servicios

| Campo                | Tipo           | Restricción                  | Descripción                     |
|----------------------|----------------|------------------------------|---------------------------------|
| id                   | BIGINT UNSIGNED| PK, Auto Increment           | Identificador del servicio      |
| categoria_servicio_id| BIGINT UNSIGNED| FK -> categorias_servicios.id| Categoría del servicio          |
| nombre               | VARCHAR(100)   | NOT NULL                     | Nombre del servicio             |
| descripcion          | TEXT           | NULL                         | Descripción del servicio        |
| precioBase           | DECIMAL(10,2)  | NOT NULL                     | Precio base del servicio        |
| tiempoEstimadoHoras  | DECIMAL(5,2)   | NOT NULL                     | Tiempo estimado en horas        |

### Tabla: detalle_repuesto (Tabla Pivot)

| Campo                      | Tipo           | Restricción                         | Descripción               |
|----------------------------|----------------|-------------------------------------|---------------------------|
| id                         | BIGINT UNSIGNED| PK, Auto Increment                  | Identificador              |
| detalle_orden_trabajo_id   | BIGINT UNSIGNED| FK -> detalles_orden_trabajo.id     | Detalle asociado           |
| repuesto_id                | BIGINT UNSIGNED| FK -> repuestos.id                  | Repuesto utilizado         |
| cantidad                   | INTEGER        | DEFAULT 1                           | Cantidad utilizada         |
| precioVenta                | DECIMAL(10,2)  | NOT NULL                            | Precio de venta al cliente |

---

# CAPÍTULO V. ARQUITECTURA DEL SISTEMA

## 5.1 Arquitectura Lógica

El sistema sigue una arquitectura de tres capas lógicas:

1. **Capa de Presentación (Frontend):** React.js con Inertia.js (SPA).
2. **Capa de Lógica de Negocio (Backend):** Laravel 13 con patrón MVC + Service Layer.
3. **Capa de Datos:** MySQL 8.0 con Eloquent ORM.

## 5.2 Arquitectura Física

> **[INSERTAR IMAGEN: Diagrama de Arquitectura Física]**
> Diagrama mostrando: Navegador del usuario -> Servidor Web (Apache) -> PHP/Laravel -> MySQL.

## 5.3 Arquitectura de Software

Patrones de diseño implementados:
- **MVC (Model-View-Controller):** Patrón principal de Laravel.
- **Service Layer:** Los controladores delegan la lógica a clases Service.
- **Inertia.js Protocol:** Conecta Laravel con React.js sin necesidad de API REST.

## 5.4 Arquitectura de Despliegue

> **[INSERTAR IMAGEN: Diagrama de Arquitectura de Despliegue]**
> Diagrama mostrando los contenedores Docker y sus conexiones.

Contenedores:
- taller-app: PHP 8.3 + Apache + Laravel + React (Puerto 8000)
- taller-db: MySQL 8.0 (Puerto 3306)
- Red interna Docker para comunicación entre contenedores.
- Volumen persistente db_data para los datos de MySQL.

---

# CAPÍTULO VI. DESARROLLO DEL SISTEMA

## 6.1 Tecnologías Utilizadas

| Tecnología        | Versión  | Propósito                                          |
|-------------------|----------|-----------------------------------------------------|
| PHP               | 8.3+     | Lenguaje de programación backend                     |
| Laravel           | 13.21    | Framework MVC principal                              |
| React.js          | 19.x     | Biblioteca frontend para interfaces reactivas        |
| Inertia.js        | 3.x      | Protocolo para conectar Laravel con React (SPA)      |
| Vite              | 8.x      | Bundler y servidor de desarrollo para el frontend    |
| MySQL             | 8.0      | Base de datos relacional                             |
| Bootstrap         | 5.3      | Framework CSS para estilos y componentes             |
| Bootstrap Icons   | 1.x      | Iconografía vectorial                                |
| DomPDF            | 3.x      | Generación de PDFs desde vistas Blade                |
| Docker            | Latest   | Contenerización de la aplicación                     |
| Git / GitHub      | Latest   | Control de versiones y repositorio remoto            |

## 6.2 Estructura del Proyecto Laravel

```
examen-parcial/
├── app/
│   ├── Http/Controllers/         # 9 controladores
│   ├── Models/                   # 15 modelos Eloquent
│   └── Services/                 # 3 clases de servicio
├── resources/
│   ├── js/
│   │   ├── Layouts/AdminLayout.jsx
│   │   └── Pages/
│   │       ├── Auth/Login.jsx
│   │       ├── Dashboard.jsx
│   │       ├── Taller/ (Kanban, NuevaOrden, OrdenDetalle)
│   │       ├── Inventario/ (Index, Kardex, Precios, Servicios)
│   │       ├── Reportes/DashboardFinanciero.jsx
│   │       └── Cliente/Seguimiento.jsx
│   └── views/ (app.blade.php, login.blade.php, pdf/factura.blade.php)
├── routes/web.php
├── database/migrations/          # 7 migraciones
├── Dockerfile
├── compose.yaml
├── .dockerignore
└── .env.example
```

## 6.3 Modelos

El sistema cuenta con 15 modelos Eloquent: User, Cliente, Vehiculo, MarcaVehiculo, ModeloVehiculo, OrdenTrabajo, DetalleOrdenTrabajo, Servicio, CategoriaServicio, Repuesto, CategoriaRepuesto, Proveedor, Inventario, MovimientoInventario, Sucursal.

## 6.4 Controladores

| Controlador                  | Responsabilidad                                                  |
|------------------------------|------------------------------------------------------------------|
| AuthController               | Login, Logout y autenticación de usuarios.                       |
| DashboardController          | Estadísticas generales del taller (KPIs del Dashboard).          |
| OrdenTrabajoController       | CRUD de órdenes, Kanban, agregar/eliminar servicios y repuestos. |
| RepuestoController           | CRUD de productos del catálogo (repuestos).                      |
| ServicioController           | CRUD de servicios (mano de obra) y categorías.                   |
| InventarioController         | Gestión de Kardex y precios.                                     |
| ReporteController            | Dashboard financiero, exportación CSV y generación de PDF.       |
| SeguimientoClienteController | Portal público de seguimiento por placa.                         |

## 6.5 Rutas

Rutas organizadas en routes/web.php:
- Rutas públicas (guest): Login (/login).
- Rutas de seguimiento (público): /seguimiento.
- Rutas protegidas (auth): Dashboard, Inventario, Taller, Reportes.

## 6.6 Vistas (React + Inertia)

Las vistas se construyen con React.js + Inertia.js (SPA). Vistas Blade solo para:
- app.blade.php: Template raíz Inertia/React.
- login.blade.php: Formulario de inicio de sesión.
- pdf/factura.blade.php: Plantilla para facturas PDF.

## 6.7 Middleware y Autenticación

- Middleware auth: Protege todas las rutas del panel de administración.
- Middleware guest: Protege las rutas de login.
- Autenticación nativa de Laravel con Auth::attempt() y Bcrypt.

## 6.8 Integración con MySQL

Conexión configurada en .env con DB_CONNECTION=mysql.
Las migraciones crean automáticamente todas las tablas con php artisan migrate.

## 6.9 Git y GitHub

> **[INSERTAR URL DEL REPOSITORIO GITHUB AQUÍ]**

---

# CAPÍTULO VII. IMPLEMENTACIÓN CON DOCKER

## 7.1 Dockerfile

```dockerfile
FROM php:8.3-apache

# Habilitar mod_rewrite de Apache para las rutas de Laravel
RUN a2enmod rewrite

# Instalar dependencias del sistema y extensiones de PHP requeridas
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    curl \
    nodejs \
    npm \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql gd zip

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configurar el DocumentRoot de Apache a la carpeta public de Laravel
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

WORKDIR /var/www/html
COPY . .

RUN composer install --no-interaction --optimize-autoloader --no-dev
RUN npm install
RUN npm run build

RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
```

**Explicación:**
- FROM php:8.3-apache: Imagen base con PHP y Apache.
- Se instalan extensiones PHP (pdo_mysql, gd, zip) y Node.js/npm.
- Se configura Apache para apuntar a la carpeta public/ de Laravel.
- Se instalan dependencias PHP (Composer) y se compilan assets React (Vite).
- Se asignan permisos a storage/ y bootstrap/cache/.

## 7.2 compose.yaml

```yaml
services:
  app:
    build:
      context: .
      dockerfile: Dockerfile
    image: taller-app
    container_name: taller-app
    ports:
      - "8000:80"
    environment:
      APP_ENV: local
      APP_KEY: "${APP_KEY}"
      DB_CONNECTION: mysql
      DB_HOST: db
      DB_PORT: 3306
      DB_DATABASE: taller_mecanico
      DB_USERNAME: root
      DB_PASSWORD: rootpassword
    depends_on:
      - db

  db:
    image: mysql:8.0
    container_name: taller-db
    restart: always
    environment:
      MYSQL_DATABASE: taller_mecanico
      MYSQL_ROOT_PASSWORD: rootpassword
    ports:
      - "3306:3306"
    volumes:
      - db_data:/var/lib/mysql

volumes:
  db_data:
```

**Explicación:**
- Dos servicios: app (Laravel) y db (MySQL).
- DB_HOST: db referencia al nombre del servicio MySQL en la red interna Docker.
- Volumen persistente db_data para los datos de MySQL.

## 7.3 .dockerignore

```
node_modules/
vendor/
.env
.git/
.phpunit.cache
storage/*.key
public/build/
tests/
phpunit.xml
```

## 7.4 .env.example

```env
APP_NAME=TallerPro
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=taller_mecanico
DB_USERNAME=root
DB_PASSWORD=
```

## 7.5 Explicación de Conceptos Docker

**Imágenes:** Plantillas de solo lectura. Se usan php:8.3-apache y mysql:8.0.

**Contenedores:** Instancias en ejecución. taller-app (la app web) y taller-db (base de datos).

**Redes:** Docker Compose crea automáticamente una red interna para que los contenedores se comuniquen entre sí usando el nombre del servicio como hostname.

**Volúmenes:** El volumen db_data almacena los datos de MySQL de forma persistente. Si se detiene el contenedor, la información se mantiene intacta.

## 7.6 Evidencias de Ejecución con Docker

### 7.6.1 docker compose build
> **[INSERTAR CAPTURA DE PANTALLA: Terminal ejecutando docker compose build]**

### 7.6.2 docker compose up -d
> **[INSERTAR CAPTURA DE PANTALLA: Terminal ejecutando docker compose up -d]**

### 7.6.3 docker compose ps
> **[INSERTAR CAPTURA DE PANTALLA: Terminal ejecutando docker compose ps]**

### 7.6.4 docker compose logs
> **[INSERTAR CAPTURA DE PANTALLA: Terminal ejecutando docker compose logs]**

### 7.6.5 Sistema ejecutándose mediante Docker
> **[INSERTAR CAPTURA DE PANTALLA: Navegador mostrando http://localhost:8000 con el login]**

---

# CAPÍTULO VIII. INVESTIGACIÓN (FORMATO IMRyD)

## Comparación e Implementación de Plataformas de Hosting para Aplicaciones Web Contenerizadas

### Introducción

**¿Qué es un Hosting?**
Un servicio de hosting es un servicio que permite publicar sitios o aplicaciones web en Internet. El proveedor pone a disposición un servidor conectado a Internet donde se alojan los archivos y la base de datos, haciéndola accesible mediante una URL pública.

**¿Qué es Docker?**
Docker es una plataforma de contenerización que permite empaquetar una aplicación junto con todas sus dependencias en un contenedor ligero y portátil, garantizando que se ejecute de manera idéntica en cualquier entorno.

**Importancia del despliegue de aplicaciones:**
El despliegue es la fase final del ciclo de vida del desarrollo de software. La contenerización con Docker facilita este proceso al eliminar las diferencias entre el entorno local y el servidor de producción.

### Metodología

Se investigaron tres plataformas de hosting con soporte Docker:
1. Render (https://render.com)
2. Railway (https://railway.app)
3. AWS - Amazon Web Services (https://aws.amazon.com)

**Criterios de comparación:** Facilidad de uso, soporte Docker, plan gratuito, soporte MySQL, tiempo de despliegue, disponibilidad.

### Resultados

#### Tabla Comparativa

| Criterio                       | Render        | Railway       | AWS (EC2+RDS)   |
|--------------------------------|---------------|---------------|-----------------|
| Facilidad de uso               | Alta          | Alta          | Media           |
| Soporte Docker                 | Si (nativo)   | Si (nativo)   | Si (ECS/EC2)    |
| Plan gratuito                  | Si (limitado) | Si ($5 crédito)| Si (12 meses)  |
| BD MySQL incluida              | No (PostgreSQL)| Si (MySQL)   | Si (RDS)        |
| Tiempo de despliegue           | ~5 minutos    | ~5 minutos    | ~30 minutos     |
| SSL automático                 | Si            | Si            | Manual          |

#### URL Pública del Proyecto
> **[INSERTAR URL PÚBLICA DEL PROYECTO DESPLEGADO]**

#### Capturas del Proceso de Despliegue
> **[INSERTAR CAPTURAS DE PANTALLA del proceso de despliegue en la plataforma elegida]**

#### Evidencias del Sistema Funcionando
> **[INSERTAR CAPTURAS DE PANTALLA del sistema funcionando desde la URL pública]**

### Discusión
> **[COMPLETAR: Justificar la plataforma elegida y comparar ventajas/desventajas]**

### Conclusiones
> **[COMPLETAR: Conclusiones de la investigación]**

---

# CAPÍTULO IX. PRUEBAS

## Matriz de Casos de Prueba

| # | Caso de Prueba | Datos de Entrada | Resultado Esperado | Resultado Obtenido | Estado |
|---|----------------|------------------|--------------------|--------------------|--------|
| 1 | Login correcto | admin@taller.com / password | Redirige al Dashboard | Redirige correctamente | Aprobado |
| 2 | Login incorrecto | admin@taller.com / 123 | Muestra error | Muestra "Credenciales incorrectas" | Aprobado |
| 3 | Crear Orden de Trabajo | Cliente: Juan, Placa: ABC-123 | Aparece en Pendientes | La orden se crea correctamente | Aprobado |
| 4 | Mover orden a En Proceso | Clic en "Empezar" | Cambia a "En Proceso" y registra hora | Se mueve y registra hora_inicio | Aprobado |
| 5 | Agregar servicio | Seleccionar "Cambio de Aceite" | Aparece en la tabla de servicios | Se agrega correctamente | Aprobado |
| 6 | Agregar repuesto con stock | Aceite Sintético, cantidad: 2 | Se agrega y stock baja en 2 | Stock se descuenta correctamente | Aprobado |
| 7 | Agregar repuesto sin stock | Repuesto con stock 0 | Sistema impide selección | Aparece gris con texto "AGOTADO" | Aprobado |
| 8 | Eliminar repuesto de orden | Clic en botón eliminar | Se elimina y stock vuelve al Kardex | Stock se devuelve correctamente | Aprobado |
| 9 | Registrar entrada Kardex | Aceite, Cantidad: 10 | Stock aumenta en 10 | Stock se incrementa correctamente | Aprobado |
| 10 | Finalizar orden | Clic en "Finalizar" | Pasa a Finalizado con hora_fin | Se mueve y registra hora_fin | Aprobado |
| 11 | Seguimiento por placa | Placa: ABC-123 | Muestra estado del vehículo | Muestra toda la información | Aprobado |
| 12 | Filtrar reportes por fecha | 29/07/2026 a 29/07/2026 | Muestra solo esa fecha | KPIs se recalculan correctamente | Aprobado |
| 13 | Exportar CSV | Clic en "Exportar a Excel" | Se descarga .csv | Se descarga y abre en Excel | Aprobado |
| 14 | Generar factura PDF | Clic en "Descargar PDF" | Se genera un PDF | PDF con información correcta | Aprobado |
| 15 | Modo claro/oscuro | Clic en ícono de tema | Cambia de tema | Todos los elementos se adaptan | Aprobado |

### Evidencias de Pruebas

> **[INSERTAR CAPTURA: Login del sistema]**
> **[INSERTAR CAPTURA: Kanban con órdenes en los 3 estados]**
> **[INSERTAR CAPTURA: Orden de trabajo con servicios y repuestos agregados]**
> **[INSERTAR CAPTURA: Kardex con movimiento de entrada registrado]**
> **[INSERTAR CAPTURA: Dashboard financiero con filtros de fecha]**
> **[INSERTAR CAPTURA: Reporte CSV abierto en Excel]**
> **[INSERTAR CAPTURA: Factura PDF generada]**
> **[INSERTAR CAPTURA: Portal de seguimiento del cliente]**
> **[INSERTAR CAPTURA: Sistema en modo claro]**
> **[INSERTAR CAPTURA: Sistema en modo oscuro]**

---

# CAPÍTULO X. MANUAL DE INSTALACIÓN

## Procedimiento para ejecutar el proyecto localmente mediante Docker

### Requisitos Previos
1. Docker Desktop instalado (https://www.docker.com/products/docker-desktop/).
2. Git instalado (https://git-scm.com/).

### Pasos

**Paso 1:** Clonar el repositorio.
```bash
git clone https://github.com/[usuario]/[repositorio].git
cd [repositorio]
```

**Paso 2:** Copiar el archivo de variables de entorno.
```bash
cp .env.example .env
```

**Paso 3:** Editar el .env con la configuración de Docker:
```env
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=taller_mecanico
DB_USERNAME=root
DB_PASSWORD=rootpassword
```

**Paso 4:** Construir los contenedores.
```bash
docker compose build
```

**Paso 5:** Iniciar los contenedores.
```bash
docker compose up -d
```

**Paso 6:** Generar la clave de la aplicación.
```bash
docker exec taller-app php artisan key:generate
```

**Paso 7:** Ejecutar las migraciones.
```bash
docker exec taller-app php artisan migrate --force
```

**Paso 8:** Acceder al sistema en http://localhost:8000

### Comandos Útiles

| Comando                          | Descripción                          |
|----------------------------------|--------------------------------------|
| docker compose up -d             | Iniciar los contenedores             |
| docker compose down              | Detener y eliminar los contenedores  |
| docker compose ps                | Ver estado de los contenedores       |
| docker compose logs              | Ver logs de los contenedores         |

---

# CAPÍTULO XI. MANUAL DE DESPLIEGUE

## Procedimiento para publicar el sistema en la plataforma de hosting elegida

> **[COMPLETAR con los pasos específicos de la plataforma elegida]**
> Ejemplo: Render, Railway, AWS u otra plataforma.

---

# CAPÍTULO XII. CONCLUSIONES Y RECOMENDACIONES

## Conclusiones

1. Se desarrolló exitosamente un sistema web integral para la gestión de talleres automotrices utilizando Laravel 13, React.js y MySQL.
2. El patrón Service Layer permitió mantener los controladores delgados y la lógica de negocio centralizada.
3. Inertia.js demostró ser una solución efectiva para construir aplicaciones SPA sin API REST separada.
4. El sistema Kardex garantiza la trazabilidad completa de los movimientos de inventario.
5. La contenerización con Docker facilita el despliegue en cualquier plataforma de hosting.
6. El portal público de seguimiento mejora significativamente la experiencia del cliente.

## Recomendaciones

1. Implementar notificaciones por correo electrónico o WhatsApp para avisar a los clientes sobre cambios de estado.
2. Desarrollar un módulo completo de asignación de mecánicos con control de disponibilidad.
3. Integrar pasarelas de pago como Stripe o PayPal.
4. Crear una aplicación móvil (PWA) para que los mecánicos actualicen estados desde sus teléfonos.
5. Configurar backups automáticos de la base de datos.

---

# REFERENCIAS BIBLIOGRÁFICAS

Laravel. (2026). Laravel Documentation. https://laravel.com/docs

React. (2026). React Documentation. https://react.dev/

Inertia.js. (2026). Inertia.js - The Modern Monolith. https://inertiajs.com/

Docker. (2026). Docker Documentation. https://docs.docker.com/

MySQL. (2026). MySQL 8.0 Reference Manual. https://dev.mysql.com/doc/refman/8.0/en/

Bootstrap. (2026). Bootstrap 5.3 Documentation. https://getbootstrap.com/docs/5.3/

Vite. (2026). Vite - Next Generation Frontend Tooling. https://vite.dev/

Naciones Unidas. (2015). Objetivos de Desarrollo Sostenible. https://www.un.org/sustainabledevelopment/es/

---

# ANEXOS

## Anexo A: Repositorio GitHub
> **[INSERTAR URL DEL REPOSITORIO]**

## Anexo B: URL Pública
> **[INSERTAR URL DEL SISTEMA DESPLEGADO]**

## Anexo C: Video de Demostración
> **[INSERTAR ENLACE AL VIDEO]**

---

# LISTA DE VERIFICACIÓN FINAL

| Entregable                                      | Estado     |
|--------------------------------------------------|------------|
| Documento en Word y PDF                          | Pendiente  |
| Repositorio GitHub actualizado                   | Pendiente  |
| Dockerfile y compose.yaml incluidos              | Completado |
| Proyecto funcional mediante Docker               | Pendiente  |
| Proyecto publicado en hosting con URL pública    | Pendiente  |
| Investigación IMRyD completa                     | Pendiente  |
| Video de demostración                            | Pendiente  |
| Manual de instalación y despliegue               | Completado |
| Norma APA 7 aplicada                             | Pendiente  |
