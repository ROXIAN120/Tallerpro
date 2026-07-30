import React from 'react';
import { Head, Link, router } from '@inertiajs/react';
import AdminLayout from '../../Layouts/AdminLayout';

export default function HistorialVehiculo({ cliente, vehiculo, historial }) {
    const getBadgeClass = (estado) => {
        if (estado === 'FINALIZADO' || estado === 'ENTREGADO') return 'bg-success text-white';
        if (estado === 'EN REPARACION' || estado === 'DIAGNOSTICO') return 'bg-primary-accent text-white';
        return 'bg-secondary text-white';
    };

    const eliminarCliente = () => {
        if (historial.length > 0) {
            alert("No se puede eliminar este cliente porque ya tiene órdenes de trabajo registradas.");
            return;
        }
        
        if (confirm("¿Estás seguro de eliminar este cliente y su vehículo de la base de datos? Esta acción no se puede deshacer.")) {
            router.delete(`/clientes/vehiculo/${vehiculo.placa}`);
        }
    };

    const content = (
        <>
            <Head title={`Historial - ${vehiculo.placa}`} />

            <div className="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h3 className="fw-bold text-white mb-1">Historial del Vehículo: {vehiculo.placa}</h3>
                    <p className="text-muted small mb-0">Listado de reparaciones y servicios previos.</p>
                </div>
                <div className="d-flex gap-2">
                    {historial.length === 0 && (
                        <button onClick={eliminarCliente} className="btn btn-outline-danger">
                            <i className="bi bi-trash me-2"></i>Eliminar Cliente
                        </button>
                    )}
                    <Link href="/clientes/directorio" className="btn btn-outline-secondary">
                        <i className="bi bi-arrow-left me-2"></i>Volver
                    </Link>
                </div>
            </div>

            <div className="row mb-5">
                <div className="col-md-6">
                    <div className="glass-panel p-4 h-100">
                        <h5 className="text-primary-accent fw-bold mb-3"><i className="bi bi-person-fill me-2"></i>Datos del Cliente</h5>
                        <div className="d-flex flex-column gap-2">
                            <div className="text-white"><strong>Nombre:</strong> {cliente.nombreCompleto}</div>
                            <div className="text-white"><strong>Teléfono:</strong> {cliente.telefono}</div>
                            <div className="text-white"><strong>Email:</strong> {cliente.email}</div>
                        </div>
                        {/* Placeholder for future WhatsApp/n8n integration */}
                        <div className="mt-4 pt-3 border-top border-secondary border-opacity-25">
                            <button className="btn btn-success btn-sm opacity-50" disabled title="Próximamente: Integración WhatsApp">
                                <i className="bi bi-whatsapp me-2"></i>Enviar Promoción (Próximamente)
                            </button>
                        </div>
                    </div>
                </div>
                <div className="col-md-6">
                    <div className="glass-panel p-4 h-100">
                        <h5 className="text-primary-accent fw-bold mb-3"><i className="bi bi-car-front-fill me-2"></i>Datos del Vehículo</h5>
                        <div className="d-flex flex-column gap-2">
                            <div className="text-white"><strong>Placa:</strong> {vehiculo.placa}</div>
                            <div className="text-white"><strong>Año:</strong> {vehiculo.anio}</div>
                            <div className="text-white"><strong>Color:</strong> {vehiculo.color}</div>
                        </div>
                    </div>
                </div>
            </div>

            <h5 className="fw-bold text-white mb-4"><i className="bi bi-clock-history me-2 text-primary-accent"></i>Historial de Órdenes</h5>
            
            <div className="row">
                <div className="col-12">
                    {historial.length === 0 ? (
                        <div className="glass-panel p-5 text-center">
                            <i className="bi bi-inbox fs-1 text-muted mb-3 d-block"></i>
                            <h5 className="text-white">No hay historial</h5>
                            <p className="text-muted">Este vehículo no tiene órdenes de trabajo registradas previamente.</p>
                        </div>
                    ) : (
                        historial.map(orden => (
                            <div key={orden.id} className="glass-panel p-4 mb-4">
                                <div className="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom border-secondary border-opacity-25">
                                    <div>
                                        <h5 className="text-white fw-bold mb-0">Orden #{orden.id}</h5>
                                        <div className="text-muted small"><i className="bi bi-calendar3 me-1"></i>{orden.fechaIngreso}</div>
                                    </div>
                                    <div className="text-end">
                                        <span className={`badge px-3 py-2 ${getBadgeClass(orden.estado)} mb-2 d-inline-block`}>
                                            {orden.estado}
                                        </span>
                                        <div className="text-success fw-bold fs-5">${parseFloat(orden.total).toFixed(2)}</div>
                                    </div>
                                </div>
                                
                                <div className="mb-3">
                                    <h6 className="text-muted fw-bold">Diagnóstico:</h6>
                                    <p className="text-white mb-0">{orden.diagnostico || 'Sin diagnóstico registrado'}</p>
                                </div>

                                <div>
                                    <h6 className="text-muted fw-bold">Trabajos Realizados:</h6>
                                    <ul className="list-group list-group-flush bg-transparent">
                                        {orden.detalles.length === 0 ? (
                                            <li className="list-group-item bg-transparent text-muted px-0">No hay servicios registrados</li>
                                        ) : (
                                            orden.detalles.map((detalle, idx) => (
                                                <li key={idx} className="list-group-item bg-transparent text-white border-secondary border-opacity-25 px-0">
                                                    <i className="bi bi-check2-circle text-primary-accent me-2"></i>
                                                    {detalle.servicio}
                                                    {detalle.repuestos.length > 0 && (
                                                        <div className="small text-muted ms-4 mt-1">
                                                            <i className="bi bi-box-seam me-1"></i>
                                                            Repuestos: {detalle.repuestos.join(', ')}
                                                        </div>
                                                    )}
                                                </li>
                                            ))
                                        )}
                                    </ul>
                                </div>
                            </div>
                        ))
                    )}
                </div>
            </div>
        </>
    );

    return <AdminLayout>{content}</AdminLayout>;
}
