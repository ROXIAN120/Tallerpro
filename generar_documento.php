<?php
require_once __DIR__ . '/vendor/autoload.php';

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\SimpleType\Jc;
use PhpOffice\PhpWord\Style\Font;

$phpWord = new PhpWord();

// Configuración global
$phpWord->setDefaultFontName('Times New Roman');
$phpWord->setDefaultFontSize(12);

// Estilos de título
$phpWord->addTitleStyle(1, ['bold' => true, 'size' => 16, 'color' => '000000'], ['alignment' => Jc::CENTER, 'spaceAfter' => 240]);
$phpWord->addTitleStyle(2, ['bold' => true, 'size' => 14, 'color' => '000000'], ['spaceAfter' => 200]);
$phpWord->addTitleStyle(3, ['bold' => true, 'size' => 13, 'color' => '000000'], ['spaceAfter' => 160]);

$sectionStyle = ['marginTop' => 1440, 'marginBottom' => 1440, 'marginLeft' => 1440, 'marginRight' => 1440];

// ============ PORTADA ============
$section = $phpWord->addSection($sectionStyle);
$section->addTextBreak(4);
$section->addText('UNIVERSIDAD [NOMBRE DE LA UNIVERSIDAD]', ['bold' => true, 'size' => 14], ['alignment' => Jc::CENTER]);
$section->addText('FACULTAD [NOMBRE DE LA FACULTAD]', ['bold' => true, 'size' => 13], ['alignment' => Jc::CENTER]);
$section->addText('CARRERA DE INGENIERÍA DE SISTEMAS', ['bold' => true, 'size' => 13], ['alignment' => Jc::CENTER]);
$section->addTextBreak(2);
$section->addText('ASIGNATURA: PROGRAMACIÓN WEB II', ['bold' => true, 'size' => 12], ['alignment' => Jc::CENTER]);
$section->addTextBreak(3);
$section->addText('PROYECTO FINAL', ['bold' => true, 'size' => 18], ['alignment' => Jc::CENTER]);
$section->addTextBreak(1);
$section->addText('Sistema de Gestión Integral para Taller Automotriz', ['bold' => true, 'size' => 16], ['alignment' => Jc::CENTER]);
$section->addText('"TallerPro"', ['bold' => true, 'size' => 16, 'italic' => true], ['alignment' => Jc::CENTER]);
$section->addTextBreak(4);
$section->addText('Integrantes:', ['bold' => true, 'size' => 12], ['alignment' => Jc::CENTER]);
$section->addText('[Nombre Apellido del Integrante 1]', ['size' => 12], ['alignment' => Jc::CENTER]);
$section->addText('[Nombre Apellido del Integrante 2]', ['size' => 12], ['alignment' => Jc::CENTER]);
$section->addTextBreak(2);
$section->addText('Docente: [Nombre del Docente]', ['bold' => true, 'size' => 12], ['alignment' => Jc::CENTER]);
$section->addTextBreak(2);
$section->addText('Gestión 2026', ['size' => 12], ['alignment' => Jc::CENTER]);
$section->addText('29 de julio de 2026', ['size' => 12], ['alignment' => Jc::CENTER]);

// ============ HOJA DE CONTROL DOCUMENTAL ============
$section = $phpWord->addSection($sectionStyle);
$section->addTitle('HOJA DE CONTROL DOCUMENTAL', 1);
$table = $section->addTable(['borderSize' => 6, 'borderColor' => '000000', 'cellMargin' => 80]);
$cellStyle = ['valign' => 'center'];
$headerStyle = ['bold' => true, 'size' => 11];
$cellTextStyle = ['size' => 11];
$table->addRow();
$table->addCell(1500, $cellStyle)->addText('Versión', $headerStyle, ['alignment' => Jc::CENTER]);
$table->addCell(2000, $cellStyle)->addText('Fecha', $headerStyle, ['alignment' => Jc::CENTER]);
$table->addCell(4000, $cellStyle)->addText('Descripción del cambio', $headerStyle, ['alignment' => Jc::CENTER]);
$table->addCell(2500, $cellStyle)->addText('Responsable', $headerStyle, ['alignment' => Jc::CENTER]);
$table->addRow();
$table->addCell(1500)->addText('1.0', $cellTextStyle, ['alignment' => Jc::CENTER]);
$table->addCell(2000)->addText('29/07/2026', $cellTextStyle, ['alignment' => Jc::CENTER]);
$table->addCell(4000)->addText('Creación inicial del documento', $cellTextStyle);
$table->addCell(2500)->addText('[Nombre]', $cellTextStyle, ['alignment' => Jc::CENTER]);
$table->addRow();
$table->addCell(1500)->addText('1.1', $cellTextStyle, ['alignment' => Jc::CENTER]);
$table->addCell(2000)->addText('[Fecha]', $cellTextStyle, ['alignment' => Jc::CENTER]);
$table->addCell(4000)->addText('Revisión y correcciones finales', $cellTextStyle);
$table->addCell(2500)->addText('[Nombre]', $cellTextStyle, ['alignment' => Jc::CENTER]);

// ============ ÍNDICES ============
$section = $phpWord->addSection($sectionStyle);
$section->addTitle('ÍNDICE GENERAL', 1);
$section->addText('(Generar automáticamente en Word: pestaña Referencias → Tabla de Contenido)', ['italic' => true, 'color' => '888888']);
$section->addTextBreak(2);
$section->addTitle('ÍNDICE DE FIGURAS', 1);
$section->addText('(Generar automáticamente en Word: pestaña Referencias → Insertar Tabla de Ilustraciones)', ['italic' => true, 'color' => '888888']);
$section->addTextBreak(2);
$section->addTitle('ÍNDICE DE TABLAS', 1);
$section->addText('(Generar automáticamente en Word: pestaña Referencias → Insertar Tabla de Ilustraciones → Tabla)', ['italic' => true, 'color' => '888888']);

// ============ CAPÍTULO I ============
$section = $phpWord->addSection($sectionStyle);
$section->addTitle('CAPÍTULO I. INTRODUCCIÓN', 1);

$section->addTitle('1.1 Antecedentes', 2);
$section->addText('En Bolivia, la industria automotriz ha experimentado un crecimiento sostenido en los últimos años. Según el Instituto Nacional de Estadística (INE), el parque automotor boliviano supera los 2.5 millones de unidades registradas. Este crecimiento genera una demanda creciente de servicios de mantenimiento y reparación vehicular. Sin embargo, la mayoría de los talleres mecánicos en el país operan de manera informal, registrando sus operaciones en cuadernos físicos, hojas de cálculo o, en el peor de los casos, de memoria. Esta situación provoca pérdida de información, descontrol del inventario de repuestos, imposibilidad de dar seguimiento al estado de los vehículos y una gestión financiera deficiente.', [], ['alignment' => Jc::BOTH]);

$section->addTitle('1.2 Planteamiento del Problema', 2);
$section->addText('Los talleres mecánicos enfrentan múltiples desafíos operativos: no tienen un sistema centralizado para registrar órdenes de trabajo, no pueden controlar el stock de repuestos en tiempo real, los clientes no tienen forma de consultar el estado de su vehículo sin llamar por teléfono, y la generación de reportes financieros es un proceso manual y propenso a errores. Estos problemas resultan en pérdida de clientes, desperdicio de recursos y una rentabilidad inferior a la esperada.', [], ['alignment' => Jc::BOTH]);

$section->addTitle('1.3 Justificación', 2);
$section->addTitle('Justificación Técnica', 3);
$section->addText('El proyecto aplica tecnologías modernas de desarrollo web como Laravel 13 (PHP), React.js, Inertia.js, MySQL y Docker, cumpliendo con estándares de la industria en arquitectura MVC, patrón de diseño Service Layer y despliegue contenerizado.', [], ['alignment' => Jc::BOTH]);

$section->addTitle('Justificación Social', 3);
$section->addText('El sistema beneficia directamente a los propietarios de talleres mecánicos al digitalizar sus operaciones, reduciendo tiempos de atención y mejorando la experiencia del cliente con un portal de seguimiento público.', [], ['alignment' => Jc::BOTH]);

$section->addTitle('Justificación Académica', 3);
$section->addText('El proyecto integra los conocimientos adquiridos durante la asignatura de Programación Web II: desarrollo backend con Laravel, frontend reactivo con React, integración con bases de datos relacionales, contenerización con Docker y despliegue en la nube.', [], ['alignment' => Jc::BOTH]);

$section->addTitle('1.4 Objetivo General', 2);
$section->addText('Desarrollar un sistema web integral para la gestión de talleres automotrices que permita administrar órdenes de trabajo, inventario de repuestos, catálogo de servicios, seguimiento del cliente y reportes financieros, utilizando Laravel como framework principal, contenerizado con Docker y desplegado en un hosting público.', [], ['alignment' => Jc::BOTH]);

$section->addTitle('1.5 Objetivos Específicos', 2);
$objetivos = [
    'Implementar un módulo de autenticación con roles de usuario (administrador).',
    'Desarrollar un tablero Kanban para la gestión visual de órdenes de trabajo (Pendiente → En Proceso → Finalizado).',
    'Crear un módulo de inventario con control de stock mediante sistema Kardex (entradas y salidas).',
    'Implementar un catálogo de servicios y repuestos con gestión de precios y márgenes de ganancia.',
    'Desarrollar un portal público de seguimiento para que los clientes consulten el estado de su vehículo por número de placa.',
    'Generar reportes financieros dinámicos con filtros por rango de fechas y exportación a Excel (CSV).',
    'Implementar la generación de facturas en formato PDF.',
    'Contenerizar la aplicación con Docker y desplegarla en un hosting accesible desde Internet.',
];
foreach ($objetivos as $i => $obj) {
    $section->addListItem(($i+1) . '. ' . $obj, 0, [], [], ['alignment' => Jc::BOTH]);
}

$section->addTitle('1.6 Alcance', 2);
$alcances = [
    'Dashboard Gerencial: Panel de estadísticas generales del taller.',
    'Kanban Operativo: Gestión visual del flujo de trabajo con estados.',
    'Catálogo de Repuestos: CRUD completo con gestión de proveedores y categorías.',
    'Catálogo de Servicios: CRUD completo con categorías y precios base.',
    'Kardex de Inventario: Registro de movimientos de entrada/salida con trazabilidad.',
    'Gestión de Precios: Configuración de costos y márgenes de ganancia por repuesto.',
    'Órdenes de Trabajo: Creación, edición, asignación de servicios y repuestos, finalización.',
    'Portal del Cliente: Seguimiento público del estado del vehículo por placa.',
    'Reportes Financieros: Dashboard con KPIs, filtros por fecha y exportación CSV.',
    'Facturación PDF: Generación de facturas profesionales por orden finalizada.',
];
foreach ($alcances as $a) {
    $section->addListItem($a, 0, [], [], ['alignment' => Jc::BOTH]);
}

$section->addTitle('1.7 Limitaciones', 2);
$lims = [
    'El sistema no implementa un módulo de nómina o recursos humanos para los mecánicos.',
    'No se implementa un sistema de notificaciones por correo electrónico o SMS.',
    'El módulo de pagos está diseñado pero no incluye integración con pasarelas de pago en línea.',
    'La aplicación móvil nativa queda fuera del alcance (aunque el sistema es responsive).',
];
foreach ($lims as $l) {
    $section->addListItem($l, 0, [], [], ['alignment' => Jc::BOTH]);
}

$section->addTitle('1.8 Eje Transversal y ODS', 2);
$section->addText('El proyecto se alinea con los siguientes Objetivos de Desarrollo Sostenible (ODS) de las Naciones Unidas:', [], ['alignment' => Jc::BOTH]);
$section->addTextBreak(1);
$section->addText('ODS 8 – Trabajo Decente y Crecimiento Económico: ', ['bold' => true]);
$section->addText('Al digitalizar las operaciones de talleres mecánicos, se promueve la formalización de pequeñas empresas y se incrementa su competitividad económica.', [], ['alignment' => Jc::BOTH]);
$section->addTextBreak(1);
$section->addText('ODS 9 – Industria, Innovación e Infraestructura: ', ['bold' => true]);
$section->addText('El sistema introduce innovación tecnológica en un sector tradicionalmente manual, modernizando la infraestructura digital de los talleres automotrices.', [], ['alignment' => Jc::BOTH]);
$section->addTextBreak(1);
$section->addText('ODS 12 – Producción y Consumo Responsables: ', ['bold' => true]);
$section->addText('El módulo Kardex permite un control preciso del inventario de repuestos, reduciendo el desperdicio y optimizando el uso de recursos materiales.', [], ['alignment' => Jc::BOTH]);

// ============ CAPÍTULO II ============
$section = $phpWord->addSection($sectionStyle);
$section->addTitle('CAPÍTULO II. INGENIERÍA DE REQUERIMIENTOS', 1);

$section->addTitle('2.1 Requerimientos Funcionales', 2);
$rfTable = $section->addTable(['borderSize' => 6, 'borderColor' => '000000', 'cellMargin' => 80]);
$rfTable->addRow();
$rfTable->addCell(1000)->addText('ID', ['bold' => true, 'size' => 10], ['alignment' => Jc::CENTER]);
$rfTable->addCell(7000)->addText('Requerimiento', ['bold' => true, 'size' => 10], ['alignment' => Jc::CENTER]);
$rfTable->addCell(1500)->addText('Prioridad', ['bold' => true, 'size' => 10], ['alignment' => Jc::CENTER]);
$rfs = [
    ['RF-01', 'El sistema debe permitir el inicio de sesión con credenciales (email y contraseña).', 'Alta'],
    ['RF-02', 'El sistema debe mostrar un Dashboard con estadísticas generales del taller.', 'Alta'],
    ['RF-03', 'El sistema debe permitir crear nuevas Órdenes de Trabajo registrando cliente, vehículo y diagnóstico.', 'Alta'],
    ['RF-04', 'El sistema debe gestionar las órdenes en un tablero Kanban con los estados: Pendiente, En Proceso y Finalizado.', 'Alta'],
    ['RF-05', 'El sistema debe permitir agregar servicios y repuestos a una Orden de Trabajo.', 'Alta'],
    ['RF-06', 'El sistema debe descontar automáticamente el stock del inventario al agregar un repuesto a una orden.', 'Alta'],
    ['RF-07', 'El sistema debe devolver el stock al inventario si se elimina un repuesto de una orden.', 'Alta'],
    ['RF-08', 'El sistema debe permitir el registro de productos (repuestos) con sus categorías y proveedores.', 'Alta'],
    ['RF-09', 'El sistema debe permitir la gestión de servicios (mano de obra) con categorías y precios base.', 'Alta'],
    ['RF-10', 'El sistema debe implementar un Kardex para registrar movimientos de entrada y salida de inventario.', 'Alta'],
    ['RF-11', 'El sistema debe permitir configurar el costo y margen de ganancia de cada repuesto.', 'Media'],
    ['RF-12', 'El sistema debe generar reportes financieros con filtros por rango de fechas.', 'Alta'],
    ['RF-13', 'El sistema debe permitir exportar reportes a formato CSV compatible con Excel.', 'Media'],
    ['RF-14', 'El sistema debe generar facturas en formato PDF por cada orden finalizada.', 'Alta'],
    ['RF-15', 'El sistema debe ofrecer un portal público de seguimiento por número de placa.', 'Alta'],
    ['RF-16', 'El sistema debe soportar modo oscuro y modo claro en toda la interfaz.', 'Baja'],
];
foreach ($rfs as $rf) {
    $rfTable->addRow();
    $rfTable->addCell(1000)->addText($rf[0], ['size' => 10], ['alignment' => Jc::CENTER]);
    $rfTable->addCell(7000)->addText($rf[1], ['size' => 10]);
    $rfTable->addCell(1500)->addText($rf[2], ['size' => 10], ['alignment' => Jc::CENTER]);
}

$section->addTextBreak(1);
$section->addTitle('2.2 Requerimientos No Funcionales', 2);
$rnfTable = $section->addTable(['borderSize' => 6, 'borderColor' => '000000', 'cellMargin' => 80]);
$rnfTable->addRow();
$rnfTable->addCell(1000)->addText('ID', ['bold' => true, 'size' => 10], ['alignment' => Jc::CENTER]);
$rnfTable->addCell(8500)->addText('Requerimiento', ['bold' => true, 'size' => 10], ['alignment' => Jc::CENTER]);
$rnfs = [
    ['RNF-01', 'El sistema debe ser desarrollado con el framework Laravel versión 13.'],
    ['RNF-02', 'El frontend debe utilizar React.js con Inertia.js para una experiencia SPA.'],
    ['RNF-03', 'La base de datos debe ser MySQL 8.0.'],
    ['RNF-04', 'El sistema debe ser contenerizado con Docker (Dockerfile + compose.yaml).'],
    ['RNF-05', 'La interfaz debe ser responsive y adaptarse a dispositivos móviles y de escritorio.'],
    ['RNF-06', 'El tiempo de respuesta de cualquier página no debe exceder los 3 segundos.'],
    ['RNF-07', 'El sistema debe estar desplegado y accesible desde una URL pública en Internet.'],
    ['RNF-08', 'El código fuente debe estar versionado en un repositorio de GitHub.'],
];
foreach ($rnfs as $rnf) {
    $rnfTable->addRow();
    $rnfTable->addCell(1000)->addText($rnf[0], ['size' => 10], ['alignment' => Jc::CENTER]);
    $rnfTable->addCell(8500)->addText($rnf[1], ['size' => 10]);
}

$section->addTextBreak(1);
$section->addTitle('2.3 Reglas de Negocio', 2);
$rnTable = $section->addTable(['borderSize' => 6, 'borderColor' => '000000', 'cellMargin' => 80]);
$rnTable->addRow();
$rnTable->addCell(1000)->addText('ID', ['bold' => true, 'size' => 10], ['alignment' => Jc::CENTER]);
$rnTable->addCell(8500)->addText('Regla de Negocio', ['bold' => true, 'size' => 10], ['alignment' => Jc::CENTER]);
$rns = [
    ['RN-01', 'No se puede agregar un repuesto a una orden si el stock disponible es insuficiente.'],
    ['RN-02', 'Al eliminar un repuesto de una orden, el stock debe ser devuelto automáticamente al Kardex.'],
    ['RN-03', 'Solo las órdenes con estado FINALIZADO o ENTREGADO se contabilizan en los reportes financieros.'],
    ['RN-04', 'El margen de ganancia se calcula como: Precio Venta = Costo + (Costo x Margen%).'],
    ['RN-05', 'La utilidad neta se calcula como: Ingresos Totales - Costos Operativos.'],
    ['RN-06', 'El tablero Kanban solo muestra las órdenes finalizadas de las últimas 48 horas.'],
    ['RN-07', 'Un servicio no puede ser eliminado de una orden si tiene repuestos asociados.'],
];
foreach ($rns as $rn) {
    $rnTable->addRow();
    $rnTable->addCell(1000)->addText($rn[0], ['size' => 10], ['alignment' => Jc::CENTER]);
    $rnTable->addCell(8500)->addText($rn[1], ['size' => 10]);
}

$section->addTextBreak(1);
$section->addTitle('2.4 Restricciones del Sistema', 2);
$restricciones = [
    'El sistema requiere PHP 8.3 o superior.',
    'Se requiere Node.js para la compilación de los assets del frontend (React/Vite).',
    'Se requiere MySQL 8.0 como motor de base de datos.',
    'El despliegue se realiza mediante Docker.',
];
foreach ($restricciones as $r) {
    $section->addListItem($r, 0);
}

// ============ CAPÍTULO III ============
$section = $phpWord->addSection($sectionStyle);
$section->addTitle('CAPÍTULO III. MODELADO UML', 1);

$section->addTitle('3.1 Diagrama de Casos de Uso', 2);
$section->addText('[INSERTAR IMAGEN: Diagrama de Casos de Uso aquí]', ['bold' => true, 'italic' => true, 'color' => 'FF0000', 'size' => 12], ['alignment' => Jc::CENTER]);
$section->addText('Figura 1. Diagrama de Casos de Uso del sistema TallerPro.', ['italic' => true, 'size' => 10], ['alignment' => Jc::CENTER]);
$section->addTextBreak(1);

$section->addText('Actores del Sistema:', ['bold' => true]);
$section->addListItem('Administrador: Tiene acceso completo al sistema.', 0);
$section->addListItem('Cliente (Público): Accede únicamente al portal de seguimiento.', 0);

$section->addTextBreak(1);
$section->addTitle('Descripción de Casos de Uso', 3);

// CU-01
$section->addText('CU-01: Iniciar Sesión', ['bold' => true, 'size' => 12]);
$cuTable = $section->addTable(['borderSize' => 6, 'borderColor' => '000000', 'cellMargin' => 80]);
$cuTable->addRow(); $cuTable->addCell(2500)->addText('Actor', ['bold' => true, 'size' => 10]); $cuTable->addCell(7000)->addText('Administrador', ['size' => 10]);
$cuTable->addRow(); $cuTable->addCell(2500)->addText('Precondición', ['bold' => true, 'size' => 10]); $cuTable->addCell(7000)->addText('El usuario debe estar registrado en el sistema.', ['size' => 10]);
$cuTable->addRow(); $cuTable->addCell(2500)->addText('Flujo Principal', ['bold' => true, 'size' => 10]); $cuTable->addCell(7000)->addText('1. El usuario ingresa email y contraseña. 2. El sistema valida las credenciales. 3. Se redirige al Dashboard.', ['size' => 10]);
$cuTable->addRow(); $cuTable->addCell(2500)->addText('Postcondición', ['bold' => true, 'size' => 10]); $cuTable->addCell(7000)->addText('El usuario accede al panel de administración.', ['size' => 10]);

$section->addTextBreak(1);
$section->addText('CU-02: Crear Orden de Trabajo', ['bold' => true, 'size' => 12]);
$cuTable2 = $section->addTable(['borderSize' => 6, 'borderColor' => '000000', 'cellMargin' => 80]);
$cuTable2->addRow(); $cuTable2->addCell(2500)->addText('Actor', ['bold' => true, 'size' => 10]); $cuTable2->addCell(7000)->addText('Administrador', ['size' => 10]);
$cuTable2->addRow(); $cuTable2->addCell(2500)->addText('Precondición', ['bold' => true, 'size' => 10]); $cuTable2->addCell(7000)->addText('El usuario debe estar autenticado.', ['size' => 10]);
$cuTable2->addRow(); $cuTable2->addCell(2500)->addText('Flujo Principal', ['bold' => true, 'size' => 10]); $cuTable2->addCell(7000)->addText('1. El usuario llena el formulario con datos del cliente, vehículo y diagnóstico. 2. El sistema crea automáticamente el cliente y vehículo si no existen. 3. Se crea la orden con estado PENDIENTE.', ['size' => 10]);
$cuTable2->addRow(); $cuTable2->addCell(2500)->addText('Postcondición', ['bold' => true, 'size' => 10]); $cuTable2->addCell(7000)->addText('La orden aparece en la columna Pendientes del Kanban.', ['size' => 10]);

$section->addTextBreak(1);
$section->addTitle('3.2 Diagrama de Clases', 2);
$section->addText('[INSERTAR IMAGEN: Diagrama de Clases aquí]', ['bold' => true, 'italic' => true, 'color' => 'FF0000', 'size' => 12], ['alignment' => Jc::CENTER]);
$section->addText('Figura 2. Diagrama de Clases del sistema TallerPro.', ['italic' => true, 'size' => 10], ['alignment' => Jc::CENTER]);
$section->addTextBreak(1);
$section->addText('El sistema cuenta con 15 modelos Eloquent organizados en capas: Seguridad (User, Role, Permiso), Comercial (Cliente, Vehiculo, MarcaVehiculo, ModeloVehiculo), Catálogo (Servicio, CategoriaServicio, Repuesto, CategoriaRepuesto, Proveedor), Inventario (Inventario, MovimientoInventario) y Operativa (OrdenTrabajo, DetalleOrdenTrabajo, Sucursal).', [], ['alignment' => Jc::BOTH]);

// ============ CAPÍTULO IV ============
$section = $phpWord->addSection($sectionStyle);
$section->addTitle('CAPÍTULO IV. BASE DE DATOS', 1);

$section->addTitle('4.1 Modelo Relacional', 2);
$section->addText('[INSERTAR IMAGEN: Modelo Entidad-Relación aquí]', ['bold' => true, 'italic' => true, 'color' => 'FF0000', 'size' => 12], ['alignment' => Jc::CENTER]);
$section->addText('Figura 3. Modelo Entidad-Relación de la base de datos taller_mecanico.', ['italic' => true, 'size' => 10], ['alignment' => Jc::CENTER]);

$section->addTextBreak(1);
$section->addTitle('4.2 Diccionario de Datos', 2);

// Tabla clientes
$section->addText('Tabla: clientes', ['bold' => true, 'size' => 12]);
$t = $section->addTable(['borderSize' => 6, 'borderColor' => '000000', 'cellMargin' => 60]);
$t->addRow(); $t->addCell(2200)->addText('Campo', ['bold'=>true,'size'=>9]); $t->addCell(2200)->addText('Tipo', ['bold'=>true,'size'=>9]); $t->addCell(2200)->addText('Restricción', ['bold'=>true,'size'=>9]); $t->addCell(3000)->addText('Descripción', ['bold'=>true,'size'=>9]);
$t->addRow(); $t->addCell(2200)->addText('id', ['size'=>9]); $t->addCell(2200)->addText('BIGINT UNSIGNED', ['size'=>9]); $t->addCell(2200)->addText('PK, Auto Increment', ['size'=>9]); $t->addCell(3000)->addText('Identificador único', ['size'=>9]);
$t->addRow(); $t->addCell(2200)->addText('nombreCompleto', ['size'=>9]); $t->addCell(2200)->addText('VARCHAR(150)', ['size'=>9]); $t->addCell(2200)->addText('NOT NULL', ['size'=>9]); $t->addCell(3000)->addText('Nombre del cliente', ['size'=>9]);
$t->addRow(); $t->addCell(2200)->addText('ci', ['size'=>9]); $t->addCell(2200)->addText('VARCHAR(20)', ['size'=>9]); $t->addCell(2200)->addText('NOT NULL, UNIQUE', ['size'=>9]); $t->addCell(3000)->addText('Cédula de identidad', ['size'=>9]);
$t->addRow(); $t->addCell(2200)->addText('telefono', ['size'=>9]); $t->addCell(2200)->addText('VARCHAR(20)', ['size'=>9]); $t->addCell(2200)->addText('NULL', ['size'=>9]); $t->addCell(3000)->addText('Teléfono de contacto', ['size'=>9]);

$section->addTextBreak(1);

// Tabla ordenes_trabajo
$section->addText('Tabla: ordenes_trabajo', ['bold' => true, 'size' => 12]);
$t2 = $section->addTable(['borderSize' => 6, 'borderColor' => '000000', 'cellMargin' => 60]);
$t2->addRow(); $t2->addCell(2200)->addText('Campo', ['bold'=>true,'size'=>9]); $t2->addCell(2200)->addText('Tipo', ['bold'=>true,'size'=>9]); $t2->addCell(2200)->addText('Restricción', ['bold'=>true,'size'=>9]); $t2->addCell(3000)->addText('Descripción', ['bold'=>true,'size'=>9]);
$t2->addRow(); $t2->addCell(2200)->addText('id', ['size'=>9]); $t2->addCell(2200)->addText('BIGINT UNSIGNED', ['size'=>9]); $t2->addCell(2200)->addText('PK', ['size'=>9]); $t2->addCell(3000)->addText('Identificador', ['size'=>9]);
$t2->addRow(); $t2->addCell(2200)->addText('vehiculo_id', ['size'=>9]); $t2->addCell(2200)->addText('BIGINT UNSIGNED', ['size'=>9]); $t2->addCell(2200)->addText('FK -> vehiculos.id', ['size'=>9]); $t2->addCell(3000)->addText('Vehículo asociado', ['size'=>9]);
$t2->addRow(); $t2->addCell(2200)->addText('cliente_id', ['size'=>9]); $t2->addCell(2200)->addText('BIGINT UNSIGNED', ['size'=>9]); $t2->addCell(2200)->addText('FK -> clientes.id', ['size'=>9]); $t2->addCell(3000)->addText('Cliente asociado', ['size'=>9]);
$t2->addRow(); $t2->addCell(2200)->addText('estado', ['size'=>9]); $t2->addCell(2200)->addText('ENUM', ['size'=>9]); $t2->addCell(2200)->addText('DEFAULT PENDIENTE', ['size'=>9]); $t2->addCell(3000)->addText('Estado de la orden', ['size'=>9]);
$t2->addRow(); $t2->addCell(2200)->addText('hora_inicio', ['size'=>9]); $t2->addCell(2200)->addText('DATETIME', ['size'=>9]); $t2->addCell(2200)->addText('NULL', ['size'=>9]); $t2->addCell(3000)->addText('Hora de inicio', ['size'=>9]);
$t2->addRow(); $t2->addCell(2200)->addText('hora_fin', ['size'=>9]); $t2->addCell(2200)->addText('DATETIME', ['size'=>9]); $t2->addCell(2200)->addText('NULL', ['size'=>9]); $t2->addCell(3000)->addText('Hora finalización', ['size'=>9]);

$section->addTextBreak(1);
$section->addText('(Las demás tablas del diccionario de datos se documentan en el archivo Markdown adjunto: Proyecto_Final_TallerPro.md)', ['italic' => true, 'color' => '888888', 'size' => 10]);

// ============ CAPÍTULO V ============
$section = $phpWord->addSection($sectionStyle);
$section->addTitle('CAPÍTULO V. ARQUITECTURA DEL SISTEMA', 1);

$section->addTitle('5.1 Arquitectura Lógica', 2);
$section->addText('El sistema sigue una arquitectura de tres capas lógicas:', [], ['alignment' => Jc::BOTH]);
$section->addListItem('Capa de Presentación (Frontend): React.js con Inertia.js, proporcionando una experiencia SPA.', 0);
$section->addListItem('Capa de Lógica de Negocio (Backend): Laravel 13 con patrón MVC + Service Layer.', 0);
$section->addListItem('Capa de Datos: MySQL 8.0 con Eloquent ORM.', 0);

$section->addTextBreak(1);
$section->addTitle('5.2 Arquitectura Física', 2);
$section->addText('[INSERTAR IMAGEN: Diagrama de Arquitectura Física aquí]', ['bold' => true, 'italic' => true, 'color' => 'FF0000', 'size' => 12], ['alignment' => Jc::CENTER]);
$section->addText('Figura 4. Arquitectura Física del sistema.', ['italic' => true, 'size' => 10], ['alignment' => Jc::CENTER]);

$section->addTextBreak(1);
$section->addTitle('5.3 Arquitectura de Software', 2);
$section->addText('El sistema implementa los siguientes patrones de diseño:', [], ['alignment' => Jc::BOTH]);
$section->addListItem('MVC (Model-View-Controller): Patrón principal de Laravel.', 0);
$section->addListItem('Service Layer: Los controladores delegan la lógica a clases Service.', 0);
$section->addListItem('Inertia.js Protocol: Conecta Laravel con React.js sin necesidad de API REST.', 0);

$section->addTextBreak(1);
$section->addTitle('5.4 Arquitectura de Despliegue', 2);
$section->addText('[INSERTAR IMAGEN: Diagrama de Arquitectura de Despliegue aquí]', ['bold' => true, 'italic' => true, 'color' => 'FF0000', 'size' => 12], ['alignment' => Jc::CENTER]);
$section->addText('Figura 5. Arquitectura de Despliegue con Docker.', ['italic' => true, 'size' => 10], ['alignment' => Jc::CENTER]);

// ============ CAPÍTULO VI (resumido) ============
$section = $phpWord->addSection($sectionStyle);
$section->addTitle('CAPÍTULO VI. DESARROLLO DEL SISTEMA', 1);
$section->addTitle('6.1 Tecnologías Utilizadas', 2);
$techTable = $section->addTable(['borderSize' => 6, 'borderColor' => '000000', 'cellMargin' => 80]);
$techTable->addRow();
$techTable->addCell(2500)->addText('Tecnología', ['bold' => true, 'size' => 10]);
$techTable->addCell(1500)->addText('Versión', ['bold' => true, 'size' => 10]);
$techTable->addCell(5500)->addText('Propósito', ['bold' => true, 'size' => 10]);
$techs = [
    ['PHP', '8.3+', 'Lenguaje de programación backend'],
    ['Laravel', '13.21', 'Framework MVC principal'],
    ['React.js', '19.x', 'Biblioteca frontend para interfaces reactivas'],
    ['Inertia.js', '3.x', 'Protocolo para conectar Laravel con React (SPA)'],
    ['Vite', '8.x', 'Bundler y servidor de desarrollo frontend'],
    ['MySQL', '8.0', 'Base de datos relacional'],
    ['Bootstrap', '5.3', 'Framework CSS para estilos y componentes'],
    ['DomPDF', '3.x', 'Generación de PDFs desde vistas Blade'],
    ['Docker', 'Latest', 'Contenerización de la aplicación'],
    ['Git/GitHub', 'Latest', 'Control de versiones y repositorio remoto'],
];
foreach ($techs as $tech) {
    $techTable->addRow();
    $techTable->addCell(2500)->addText($tech[0], ['size' => 10]);
    $techTable->addCell(1500)->addText($tech[1], ['size' => 10]);
    $techTable->addCell(5500)->addText($tech[2], ['size' => 10]);
}

$section->addTextBreak(1);
$section->addTitle('6.2 Estructura del Proyecto', 2);
$section->addText('El proyecto sigue la estructura estándar de Laravel con las siguientes carpetas principales: app/Http/Controllers/ (9 controladores), app/Models/ (15 modelos Eloquent), app/Services/ (3 clases de servicio), resources/js/Pages/ (vistas React), routes/web.php (rutas), database/migrations/ (7 migraciones).', [], ['alignment' => Jc::BOTH]);

$section->addTextBreak(1);
$section->addTitle('6.3 Controladores', 2);
$ctrlTable = $section->addTable(['borderSize' => 6, 'borderColor' => '000000', 'cellMargin' => 80]);
$ctrlTable->addRow();
$ctrlTable->addCell(3500)->addText('Controlador', ['bold' => true, 'size' => 10]);
$ctrlTable->addCell(6000)->addText('Responsabilidad', ['bold' => true, 'size' => 10]);
$ctrls = [
    ['AuthController', 'Login, Logout y autenticación de usuarios.'],
    ['DashboardController', 'Estadísticas generales del taller (KPIs).'],
    ['OrdenTrabajoController', 'CRUD de órdenes, Kanban, agregar/eliminar servicios y repuestos.'],
    ['RepuestoController', 'CRUD de productos del catálogo (repuestos).'],
    ['ServicioController', 'CRUD de servicios y categorías.'],
    ['InventarioController', 'Gestión de Kardex y precios.'],
    ['ReporteController', 'Dashboard financiero, exportación CSV y PDF.'],
    ['SeguimientoClienteController', 'Portal público de seguimiento por placa.'],
];
foreach ($ctrls as $ctrl) {
    $ctrlTable->addRow();
    $ctrlTable->addCell(3500)->addText($ctrl[0], ['size' => 10]);
    $ctrlTable->addCell(6000)->addText($ctrl[1], ['size' => 10]);
}

$section->addTextBreak(1);
$section->addText('6.4 Rutas, 6.5 Vistas, 6.6 Middleware y 6.7 Integración con MySQL: Documentados en detalle en el archivo Markdown adjunto.', ['italic' => true, 'size' => 10]);

// ============ CAPÍTULO VII ============
$section = $phpWord->addSection($sectionStyle);
$section->addTitle('CAPÍTULO VII. IMPLEMENTACIÓN CON DOCKER', 1);

$section->addTitle('7.1 Dockerfile', 2);
$section->addText(file_get_contents(__DIR__ . '/Dockerfile'), ['name' => 'Consolas', 'size' => 9], ['alignment' => Jc::LEFT]);

$section->addTextBreak(1);
$section->addTitle('7.2 compose.yaml', 2);
$section->addText(file_get_contents(__DIR__ . '/compose.yaml'), ['name' => 'Consolas', 'size' => 9], ['alignment' => Jc::LEFT]);

$section->addTextBreak(1);
$section->addTitle('7.3 .dockerignore', 2);
$section->addText(file_get_contents(__DIR__ . '/.dockerignore'), ['name' => 'Consolas', 'size' => 9], ['alignment' => Jc::LEFT]);

$section->addTextBreak(1);
$section->addTitle('7.4 Explicación de Conceptos Docker', 2);
$section->addText('Imágenes: ', ['bold' => true]); $section->addText('Plantillas de solo lectura. Se usan php:8.3-apache y mysql:8.0.', [], ['alignment' => Jc::BOTH]);
$section->addText('Contenedores: ', ['bold' => true]); $section->addText('Instancias en ejecución: taller-app (la app web) y taller-db (base de datos).', [], ['alignment' => Jc::BOTH]);
$section->addText('Redes: ', ['bold' => true]); $section->addText('Docker Compose crea automáticamente una red interna para que los contenedores se comuniquen.', [], ['alignment' => Jc::BOTH]);
$section->addText('Volúmenes: ', ['bold' => true]); $section->addText('El volumen db_data almacena los datos de MySQL de forma persistente.', [], ['alignment' => Jc::BOTH]);

$section->addTextBreak(1);
$section->addTitle('7.5 Evidencias de Ejecución', 2);
$evidencias = [
    'docker compose build' => 'Figura 6. Ejecución del comando docker compose build.',
    'docker compose up -d' => 'Figura 7. Ejecución del comando docker compose up -d.',
    'docker compose ps' => 'Figura 8. Ejecución del comando docker compose ps.',
    'docker compose logs' => 'Figura 9. Ejecución del comando docker compose logs.',
    'Sistema ejecutándose en http://localhost:8000' => 'Figura 10. Sistema ejecutándose mediante Docker.',
];
foreach ($evidencias as $cmd => $caption) {
    $section->addText('[INSERTAR CAPTURA DE PANTALLA: ' . $cmd . ']', ['bold' => true, 'italic' => true, 'color' => 'FF0000'], ['alignment' => Jc::CENTER]);
    $section->addText($caption, ['italic' => true, 'size' => 10], ['alignment' => Jc::CENTER]);
    $section->addTextBreak(1);
}

// ============ CAPÍTULO VIII ============
$section = $phpWord->addSection($sectionStyle);
$section->addTitle('CAPÍTULO VIII. INVESTIGACIÓN (FORMATO IMRyD)', 1);
$section->addText('Comparación e Implementación de Plataformas de Hosting para Aplicaciones Web Contenerizadas', ['bold' => true, 'size' => 13], ['alignment' => Jc::CENTER]);
$section->addTextBreak(1);

$section->addTitle('Introducción', 2);
$section->addText('Un servicio de hosting es un servicio que permite publicar sitios o aplicaciones web en Internet. Docker es una plataforma de contenerización que permite empaquetar una aplicación junto con todas sus dependencias en un contenedor ligero y portátil. El despliegue de una aplicación web es la fase final del ciclo de vida del desarrollo de software.', [], ['alignment' => Jc::BOTH]);

$section->addTitle('Metodología', 2);
$section->addText('Se investigaron tres plataformas: Render (render.com), Railway (railway.app) y AWS (aws.amazon.com). Los criterios de comparación fueron: facilidad de uso, soporte Docker, plan gratuito, soporte MySQL, tiempo de despliegue y disponibilidad.', [], ['alignment' => Jc::BOTH]);

$section->addTitle('Resultados', 2);
$compTable = $section->addTable(['borderSize' => 6, 'borderColor' => '000000', 'cellMargin' => 80]);
$compTable->addRow();
$compTable->addCell(2500)->addText('Criterio', ['bold' => true, 'size' => 10]);
$compTable->addCell(2200)->addText('Render', ['bold' => true, 'size' => 10], ['alignment' => Jc::CENTER]);
$compTable->addCell(2200)->addText('Railway', ['bold' => true, 'size' => 10], ['alignment' => Jc::CENTER]);
$compTable->addCell(2600)->addText('AWS (EC2+RDS)', ['bold' => true, 'size' => 10], ['alignment' => Jc::CENTER]);
$comps = [
    ['Facilidad de uso', 'Alta', 'Alta', 'Media'],
    ['Soporte Docker', 'Sí (nativo)', 'Sí (nativo)', 'Sí (ECS/EC2)'],
    ['Plan gratuito', 'Sí (limitado)', 'Sí ($5 crédito)', 'Sí (12 meses)'],
    ['BD MySQL', 'No (PostgreSQL)', 'Sí', 'Sí (RDS)'],
    ['Tiempo despliegue', '~5 min', '~5 min', '~30 min'],
    ['SSL automático', 'Sí', 'Sí', 'Manual'],
];
foreach ($comps as $comp) {
    $compTable->addRow();
    $compTable->addCell(2500)->addText($comp[0], ['size' => 10]);
    $compTable->addCell(2200)->addText($comp[1], ['size' => 10], ['alignment' => Jc::CENTER]);
    $compTable->addCell(2200)->addText($comp[2], ['size' => 10], ['alignment' => Jc::CENTER]);
    $compTable->addCell(2600)->addText($comp[3], ['size' => 10], ['alignment' => Jc::CENTER]);
}

$section->addTextBreak(1);
$section->addText('URL Pública: [INSERTAR URL PÚBLICA DEL PROYECTO DESPLEGADO]', ['bold' => true, 'color' => 'FF0000']);
$section->addTextBreak(1);
$section->addText('[INSERTAR CAPTURAS DE PANTALLA del proceso de despliegue y del sistema funcionando]', ['bold' => true, 'italic' => true, 'color' => 'FF0000'], ['alignment' => Jc::CENTER]);

$section->addTextBreak(1);
$section->addTitle('Discusión', 2);
$section->addText('[COMPLETAR: Justificar la plataforma elegida y comparar ventajas/desventajas frente a las demás]', ['italic' => true, 'color' => 'FF0000'], ['alignment' => Jc::BOTH]);

$section->addTitle('Conclusiones de la Investigación', 2);
$section->addText('[COMPLETAR: Conclusiones de la investigación IMRyD]', ['italic' => true, 'color' => 'FF0000'], ['alignment' => Jc::BOTH]);

// ============ CAPÍTULO IX ============
$section = $phpWord->addSection($sectionStyle);
$section->addTitle('CAPÍTULO IX. PRUEBAS', 1);

$section->addTitle('Matriz de Casos de Prueba', 2);
$testTable = $section->addTable(['borderSize' => 6, 'borderColor' => '000000', 'cellMargin' => 60]);
$testTable->addRow();
$testTable->addCell(500)->addText('#', ['bold'=>true,'size'=>8], ['alignment' => Jc::CENTER]);
$testTable->addCell(2200)->addText('Caso', ['bold'=>true,'size'=>8]);
$testTable->addCell(2000)->addText('Entrada', ['bold'=>true,'size'=>8]);
$testTable->addCell(2200)->addText('Esperado', ['bold'=>true,'size'=>8]);
$testTable->addCell(2200)->addText('Obtenido', ['bold'=>true,'size'=>8]);
$testTable->addCell(900)->addText('Estado', ['bold'=>true,'size'=>8]);
$tests = [
    ['1','Login correcto','admin@taller.com / password','Redirige al Dashboard','Redirige correctamente','OK'],
    ['2','Login incorrecto','admin@taller.com / 123','Muestra error','Muestra error','OK'],
    ['3','Crear Orden','Juan, ABC-123','Aparece en Pendientes','Se crea correctamente','OK'],
    ['4','Mover a En Proceso','Clic en Empezar','Cambia estado','Se mueve correctamente','OK'],
    ['5','Agregar servicio','Cambio de Aceite','Aparece en tabla','Se agrega correctamente','OK'],
    ['6','Agregar repuesto','Aceite, cant: 2','Stock baja en 2','Stock se descuenta','OK'],
    ['7','Repuesto sin stock','Stock = 0','Impide selección','Aparece AGOTADO','OK'],
    ['8','Eliminar repuesto','Clic eliminar','Stock vuelve al Kardex','Stock devuelto','OK'],
    ['9','Entrada Kardex','Aceite, cant: 10','Stock sube en 10','Stock incrementa','OK'],
    ['10','Finalizar orden','Clic Finalizar','Pasa a Finalizado','Se mueve correctamente','OK'],
    ['11','Seguimiento placa','Placa: ABC-123','Muestra estado','Muestra info','OK'],
    ['12','Filtrar reportes','29/07 a 29/07','Solo esa fecha','KPIs correctos','OK'],
    ['13','Exportar CSV','Clic Exportar','Descarga .csv','Se descarga','OK'],
    ['14','Factura PDF','Clic PDF','Genera PDF','PDF correcto','OK'],
    ['15','Modo claro/oscuro','Clic tema','Cambia tema','Se adapta','OK'],
];
foreach ($tests as $test) {
    $testTable->addRow();
    foreach ($test as $cell) {
        $testTable->addCell()->addText($cell, ['size' => 8]);
    }
}

$section->addTextBreak(1);
$section->addTitle('Evidencias de Pruebas', 2);
$capturas = [
    'Figura 11. Login del sistema.',
    'Figura 12. Kanban con órdenes en los 3 estados.',
    'Figura 13. Orden de trabajo con servicios y repuestos.',
    'Figura 14. Kardex con movimiento de entrada.',
    'Figura 15. Dashboard financiero con filtros.',
    'Figura 16. Reporte CSV abierto en Excel.',
    'Figura 17. Factura PDF generada.',
    'Figura 18. Portal de seguimiento del cliente.',
];
foreach ($capturas as $cap) {
    $section->addText('[INSERTAR CAPTURA DE PANTALLA]', ['bold' => true, 'italic' => true, 'color' => 'FF0000'], ['alignment' => Jc::CENTER]);
    $section->addText($cap, ['italic' => true, 'size' => 10], ['alignment' => Jc::CENTER]);
    $section->addTextBreak(1);
}

// ============ CAPÍTULO X ============
$section = $phpWord->addSection($sectionStyle);
$section->addTitle('CAPÍTULO X. MANUAL DE INSTALACIÓN', 1);
$section->addText('Procedimiento para ejecutar el proyecto localmente mediante Docker:', ['bold' => true]);
$section->addTextBreak(1);
$section->addText('Requisitos Previos:', ['bold' => true]);
$section->addListItem('Docker Desktop instalado (https://www.docker.com/products/docker-desktop/).', 0);
$section->addListItem('Git instalado (https://git-scm.com/).', 0);
$section->addTextBreak(1);
$pasos = [
    'Paso 1: Clonar el repositorio. git clone https://github.com/[usuario]/[repositorio].git',
    'Paso 2: Copiar el archivo de variables de entorno. cp .env.example .env',
    'Paso 3: Editar el .env con DB_HOST=db, DB_DATABASE=taller_mecanico, DB_USERNAME=root, DB_PASSWORD=rootpassword',
    'Paso 4: Construir los contenedores. docker compose build',
    'Paso 5: Iniciar los contenedores. docker compose up -d',
    'Paso 6: Generar la clave. docker exec taller-app php artisan key:generate',
    'Paso 7: Ejecutar migraciones. docker exec taller-app php artisan migrate --force',
    'Paso 8: Acceder al sistema en http://localhost:8000',
];
foreach ($pasos as $p) {
    $section->addListItem($p, 0, [], [], ['alignment' => Jc::BOTH]);
}

// ============ CAPÍTULO XI ============
$section = $phpWord->addSection($sectionStyle);
$section->addTitle('CAPÍTULO XI. MANUAL DE DESPLIEGUE', 1);
$section->addText('[COMPLETAR: Procedimiento para publicar el sistema en la plataforma de hosting elegida (Render, Railway, AWS u otra)]', ['italic' => true, 'color' => 'FF0000'], ['alignment' => Jc::BOTH]);

// ============ CAPÍTULO XII ============
$section = $phpWord->addSection($sectionStyle);
$section->addTitle('CAPÍTULO XII. CONCLUSIONES Y RECOMENDACIONES', 1);

$section->addTitle('Conclusiones', 2);
$conclusiones = [
    'Se desarrolló exitosamente un sistema web integral para la gestión de talleres automotrices utilizando Laravel 13, React.js y MySQL.',
    'El patrón Service Layer permitió mantener los controladores delgados y la lógica de negocio centralizada.',
    'Inertia.js demostró ser una solución efectiva para construir aplicaciones SPA sin API REST separada.',
    'El sistema Kardex garantiza la trazabilidad completa de los movimientos de inventario.',
    'La contenerización con Docker facilita el despliegue en cualquier plataforma de hosting.',
    'El portal público de seguimiento mejora significativamente la experiencia del cliente.',
];
foreach ($conclusiones as $i => $c) {
    $section->addListItem(($i+1) . '. ' . $c, 0, [], [], ['alignment' => Jc::BOTH]);
}

$section->addTextBreak(1);
$section->addTitle('Recomendaciones', 2);
$recomendaciones = [
    'Implementar notificaciones por correo electrónico o WhatsApp.',
    'Desarrollar un módulo de asignación de mecánicos con control de disponibilidad.',
    'Integrar pasarelas de pago como Stripe o PayPal.',
    'Crear una aplicación móvil (PWA) para los mecánicos.',
    'Configurar backups automáticos de la base de datos.',
];
foreach ($recomendaciones as $i => $r) {
    $section->addListItem(($i+1) . '. ' . $r, 0, [], [], ['alignment' => Jc::BOTH]);
}

// ============ REFERENCIAS ============
$section = $phpWord->addSection($sectionStyle);
$section->addTitle('REFERENCIAS BIBLIOGRÁFICAS', 1);
$refs = [
    'Laravel. (2026). Laravel Documentation. https://laravel.com/docs',
    'React. (2026). React Documentation. https://react.dev/',
    'Inertia.js. (2026). Inertia.js - The Modern Monolith. https://inertiajs.com/',
    'Docker. (2026). Docker Documentation. https://docs.docker.com/',
    'MySQL. (2026). MySQL 8.0 Reference Manual. https://dev.mysql.com/doc/refman/8.0/en/',
    'Bootstrap. (2026). Bootstrap 5.3 Documentation. https://getbootstrap.com/docs/5.3/',
    'Naciones Unidas. (2015). Objetivos de Desarrollo Sostenible. https://www.un.org/sustainabledevelopment/es/',
];
foreach ($refs as $ref) {
    $section->addText($ref, ['size' => 11], ['alignment' => Jc::BOTH, 'indentation' => ['left' => 720, 'hanging' => 720]]);
    $section->addTextBreak(0);
}

// ============ ANEXOS ============
$section = $phpWord->addSection($sectionStyle);
$section->addTitle('ANEXOS', 1);
$section->addText('Anexo A: Repositorio GitHub', ['bold' => true, 'size' => 12]);
$section->addText('[INSERTAR URL DEL REPOSITORIO]', ['italic' => true, 'color' => 'FF0000']);
$section->addTextBreak(1);
$section->addText('Anexo B: URL Pública', ['bold' => true, 'size' => 12]);
$section->addText('[INSERTAR URL DEL SISTEMA DESPLEGADO]', ['italic' => true, 'color' => 'FF0000']);
$section->addTextBreak(1);
$section->addText('Anexo C: Video de Demostración', ['bold' => true, 'size' => 12]);
$section->addText('[INSERTAR ENLACE AL VIDEO DE DEMOSTRACIÓN]', ['italic' => true, 'color' => 'FF0000']);

// Guardar
$objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
$objWriter->save(__DIR__ . '/Documentacion/Proyecto_Final_TallerPro.docx');

echo "Documento Word generado exitosamente en: Documentacion/Proyecto_Final_TallerPro.docx\n";
