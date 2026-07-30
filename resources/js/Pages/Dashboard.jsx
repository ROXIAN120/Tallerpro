import React from 'react';
import { Head, Link } from '@inertiajs/react';
import AdminLayout from '../Layouts/AdminLayout';

export default function Dashboard({ metricas, ultimasOrdenes }) {
    
    // Badge de colores dinámicos para los estados de orden
    const getBadgeClass = (estado) => {
        if (estado === 'FINALIZADO' || estado === 'ENTREGADO') return 'bg-success text-white';
        if (estado === 'EN REPARACION' || estado === 'DIAGNOSTICO') return 'bg-primary-accent text-white';
        return 'bg-secondary text-white';
    };

    const content = (
        <>
            <Head title="Dashboard Gerencial" />

            {/* Encabezado */}
            <div className="d-flex justify-content-between align-items-center mb-5">
                <div>
                    <h3 className="fw-bold text-white mb-1">Resumen Operativo</h3>
                    <p className="text-muted small mb-0">Monitoreo en tiempo real del taller.</p>
                </div>
            </div>

            {/* Tarjetas KPI Superiores */}
            <div className="row g-4 mb-5">
                {/* KPI Órdenes Activas */}
                <div className="col-md-3">
                    <div className="glass-panel p-4 d-flex align-items-center position-relative overflow-hidden h-100">
                        <div className="me-4 position-relative" style={{ zIndex: 2 }}>
                            <div className="rounded-circle d-flex justify-content-center align-items-center shadow-sm" style={{ width: '64px', height: '64px', backgroundColor: 'rgba(6, 182, 212, 0.15)' }}>
                                <i className="bi bi-tools fs-2" style={{ color: 'var(--accent-primary)' }}></i>
                            </div>
                        </div>
                        <div style={{ zIndex: 2 }}>
                            <h6 className="text-muted text-uppercase fw-bold mb-1 small tracking-wider">Órdenes Activas</h6>
                            <h2 className="text-white fw-bold mb-0">{metricas.ordenesActivas}</h2>
                        </div>
                        <i className="bi bi-tools position-absolute" style={{ fontSize: '8rem', right: '-15px', bottom: '-25px', zIndex: 1, color: 'var(--accent-primary)', transform: 'rotate(-15deg)', opacity: 0.1 }}></i>
                    </div>
                </div>

                {/* KPI Vehículos Entregados */}
                <div className="col-md-3">
                    <div className="glass-panel p-4 d-flex align-items-center position-relative overflow-hidden h-100">
                        <div className="me-4 position-relative" style={{ zIndex: 2 }}>
                            <div className="rounded-circle d-flex justify-content-center align-items-center shadow-sm" style={{ width: '64px', height: '64px', backgroundColor: 'rgba(99, 102, 241, 0.15)' }}>
                                <i className="bi bi-car-front fs-2" style={{ color: 'var(--accent-indigo)' }}></i>
                            </div>
                        </div>
                        <div style={{ zIndex: 2 }}>
                            <h6 className="text-muted text-uppercase fw-bold mb-1 small tracking-wider">Entregados (Mes)</h6>
                            <h2 className="text-white fw-bold mb-0">{metricas.vehiculosEntregados}</h2>
                        </div>
                        <i className="bi bi-car-front position-absolute" style={{ fontSize: '8rem', right: '-15px', bottom: '-25px', zIndex: 1, color: 'var(--accent-indigo)', transform: 'rotate(-15deg)', opacity: 0.1 }}></i>
                    </div>
                </div>

                {/* KPI Alertas de Stock */}
                <div className="col-md-3">
                    <div className="glass-panel p-4 d-flex align-items-center position-relative overflow-hidden h-100">
                        <div className="me-4 position-relative" style={{ zIndex: 2 }}>
                            <div className="rounded-circle d-flex justify-content-center align-items-center shadow-sm" style={{ width: '64px', height: '64px', backgroundColor: 'rgba(239, 68, 68, 0.15)' }}>
                                <i className="bi bi-exclamation-triangle fs-2 text-danger"></i>
                            </div>
                        </div>
                        <div style={{ zIndex: 2 }}>
                            <h6 className="text-muted text-uppercase fw-bold mb-1 small tracking-wider">Stock Crítico</h6>
                            <h2 className="text-white fw-bold mb-0">{metricas.alertasStock}</h2>
                        </div>
                        <i className="bi bi-exclamation-triangle position-absolute text-danger" style={{ fontSize: '8rem', right: '-15px', bottom: '-25px', zIndex: 1, transform: 'rotate(-15deg)', opacity: 0.1 }}></i>
                    </div>
                </div>
                
                {/* KPI Directorio de Clientes */}
                <div className="col-md-3">
                    <Link href="/clientes/directorio" className="text-decoration-none">
                        <div className="glass-panel p-4 d-flex align-items-center position-relative overflow-hidden h-100" style={{ cursor: 'pointer', transition: 'all 0.3s ease' }} onMouseOver={(e) => e.currentTarget.style.transform = 'translateY(-5px)'} onMouseOut={(e) => e.currentTarget.style.transform = 'translateY(0)'}>
                            <div className="me-4 position-relative" style={{ zIndex: 2 }}>
                                <div className="rounded-circle d-flex justify-content-center align-items-center shadow-sm" style={{ width: '64px', height: '64px', backgroundColor: 'rgba(16, 185, 129, 0.15)' }}>
                                    <i className="bi bi-person-lines-fill fs-2" style={{ color: '#10b981' }}></i>
                                </div>
                            </div>
                            <div style={{ zIndex: 2 }}>
                                <h6 className="text-muted text-uppercase fw-bold mb-1 small tracking-wider">Directorio</h6>
                                <h5 className="text-white fw-bold mb-0">Clientes</h5>
                            </div>
                            <i className="bi bi-journal-bookmark-fill position-absolute" style={{ fontSize: '8rem', right: '-15px', bottom: '-25px', zIndex: 1, color: '#10b981', transform: 'rotate(-15deg)', opacity: 0.1 }}></i>
                        </div>
                    </Link>
                </div>
            </div>

            {/* Fila Inferior: Tabla de Alta Densidad */}
            <div className="row">
                <div className="col-lg-12">
                    <div className="glass-panel p-0 overflow-hidden">
                        <div className="d-flex justify-content-between align-items-center p-3 px-4 border-bottom border-secondary border-opacity-25 bg-secondary bg-opacity-10">
                            <h6 className="fw-bold mb-0 text-body"><i className="bi bi-clock-history me-2 text-primary-accent"></i>Últimas Órdenes Ingresadas</h6>
                            <Link href="/taller/kanban" className="btn btn-sm btn-outline-secondary fw-semibold border-secondary">Ir a Pizarra</Link>
                        </div>
                        
                        <div className="table-responsive p-3">
                            <table className="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th className="bg-dark text-white text-uppercase" style={{ width: '10%' }}>ID</th>
                                        <th className="bg-dark text-white text-uppercase" style={{ width: '20%' }}>Fecha Ingreso</th>
                                        <th className="bg-dark text-white text-uppercase" style={{ width: '30%' }}>Cliente</th>
                                        <th className="bg-dark text-white text-uppercase" style={{ width: '15%' }}>Placa</th>
                                        <th className="bg-dark text-white text-uppercase" style={{ width: '15%' }}>Estado</th>
                                        <th className="bg-dark text-end text-white text-uppercase" style={{ width: '10%' }}>Acción</th>
                                    </tr>
                                </thead>
                                <tbody className="align-middle">
                                    {ultimasOrdenes.map(orden => (
                                        <tr key={orden.id}>
                                            <td className="fw-bold text-primary-accent">OT-{orden.id}</td>
                                            <td className="text-muted"><i className="bi bi-calendar3 me-2"></i>{orden.fecha}</td>
                                            <td className="fw-semibold">
                                                <div className="d-flex align-items-center">
                                                    <i className="bi bi-person-fill me-2 text-muted fs-5"></i>
                                                    <div>
                                                        <div className="text-white">{orden.cliente}</div>
                                                        <div className="text-muted" style={{ fontSize: '0.85em' }}>
                                                            <i className="bi bi-telephone-fill me-1" style={{ fontSize: '0.85em' }}></i>
                                                            {orden.telefono}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td><span className="badge bg-secondary bg-opacity-25 text-white border border-secondary px-2 py-1">{orden.vehiculo}</span></td>
                                            <td>
                                                <span className={`badge px-2 py-1 ${getBadgeClass(orden.estado)}`}>
                                                    {orden.estado}
                                                </span>
                                            </td>
                                            <td className="text-end text-nowrap">
                                                <Link href="/taller/kanban" className="btn btn-sm btn-link text-primary-accent p-0 text-decoration-none fw-bold small">
                                                    Ver <i className="bi bi-arrow-right-short"></i>
                                                </Link>
                                            </td>
                                        </tr>
                                    ))}
                                    {ultimasOrdenes.length === 0 && (
                                        <tr>
                                            <td colSpan="6" className="text-center py-5 text-muted">
                                                <i className="bi bi-inbox fs-2 d-block mb-2"></i>
                                                No existen órdenes de trabajo recientes.
                                            </td>
                                        </tr>
                                    )}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </>
    );
    
    return <AdminLayout>{content}</AdminLayout>;
}
