import React from 'react';
import { Head, router, Link } from '@inertiajs/react';
import AdminLayout from '../../Layouts/AdminLayout';

export default function Kanban({ pendientes, enProceso, finalizados }) {
    
    const cambiarEstado = (id, nuevoEstado) => {
        router.post('/taller/kanban/estado', {
            id: id,
            estado: nuevoEstado
        }, { 
            preserveScroll: true,
            preserveState: true
        });
    };

    const cancelarOrden = (id) => {
        if (confirm("¿Estás seguro de cancelar esta orden? Se devolverán los repuestos al inventario.")) {
            router.post('/taller/ordenes/cancelar', { id: id }, {
                preserveScroll: true,
                preserveState: true
            });
        }
    };

    const KanbanCard = ({ orden, nextAction, nextLabel, nextColor }) => (
        <div className="card mb-3 border shadow-sm glass-panel hover-scale" style={{ borderRadius: '8px' }}>
            <div className="card-body p-3">
                <div className="d-flex justify-content-between align-items-center mb-2">
                    <span className="badge bg-secondary bg-opacity-25 text-body border border-secondary fw-normal px-2 py-1" style={{ fontSize: '0.75rem' }}>{orden.vehiculo}</span>
                    <span className="text-muted fw-bold font-monospace" style={{ fontSize: '0.75rem' }}>OT-{orden.id}</span>
                </div>
                
                <h6 className="text-body fw-bold mb-1 text-truncate" style={{ fontSize: '1rem', lineHeight: '1.4' }} title={orden.servicio}>{orden.servicio}</h6>
                <div className="text-muted d-flex justify-content-between align-items-center mb-2" style={{ fontSize: '0.8rem' }}>
                    <span><i className="bi bi-person-fill me-1 opacity-50"></i>{orden.cliente}</span>
                    <span className="text-primary-accent fw-semibold"><i className="bi bi-wrench-adjustable me-1"></i>{orden.mecanico}</span>
                </div>
                
                <div className="d-flex justify-content-between align-items-center mt-2 pt-2 border-top border-secondary border-opacity-25">
                    <div className="d-flex flex-column me-2">
                        {orden.hora_inicio && !orden.hora_fin && (
                            <span className="text-warning fw-semibold text-nowrap" style={{ fontSize: '0.75rem' }}>
                                <i className="bi bi-clock-history me-1"></i>Inició: {new Date(orden.hora_inicio).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}
                            </span>
                        )}
                        {orden.hora_fin && (
                            <span className="text-success fw-semibold text-nowrap" style={{ fontSize: '0.75rem' }}>
                                <i className="bi bi-check2-all me-1"></i>Fin: {new Date(orden.hora_fin).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}
                            </span>
                        )}
                    </div>
                    <div className="d-flex gap-2 w-100 justify-content-end">
                        {orden.estado === 'PENDIENTE' ? (
                            <>
                                <button onClick={() => cancelarOrden(orden.id)} className="btn btn-sm btn-outline-danger py-1 px-2 fw-bold rounded border-0" style={{ fontSize: '0.8rem' }} title="Cancelar Orden">
                                    <i className="bi bi-x-circle"></i>
                                </button>
                                <Link href={`/taller/ordenes/${orden.id}`} className="btn btn-sm btn-primary py-1 px-3 fw-bold rounded border-0 w-100" style={{ fontSize: '0.8rem' }}>
                                    <i className="bi bi-play-circle me-1"></i>Empezar
                                </Link>
                            </>
                        ) : (
                            <>
                                <Link href={`/taller/ordenes/${orden.id}`} className="btn btn-sm btn-outline-info py-1 px-2 fw-bold rounded border-0" style={{ fontSize: '0.8rem' }} title="Editar">
                                    <i className="bi bi-pencil-square"></i>
                                </Link>
                                {orden.estado === 'EN PROCESO' && (
                                    <button 
                                        className="btn btn-sm btn-warning py-1 px-2 fw-bold rounded border-0 text-white"
                                        style={{ fontSize: '0.8rem' }}
                                        onClick={() => cambiarEstado(orden.id, 'PENDIENTE')}
                                        title="Pausar y devolver a Pendientes"
                                    >
                                        <i className="bi bi-pause-circle"></i>
                                    </button>
                                )}
                                {nextAction && (
                                    <button 
                                        className={`btn btn-sm btn-${nextColor} py-1 px-2 fw-bold rounded border-0 flex-grow-1`}
                                        style={{ fontSize: '0.8rem' }}
                                        onClick={() => cambiarEstado(orden.id, nextAction)}
                                    >
                                        {nextLabel}
                                    </button>
                                )}
                            </>
                        )}
                    </div>
                </div>
                
            </div>
        </div>
    );

    const content = (
        <>
            <Head title="Pizarra Operativa" />
            
            <div className="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 className="fw-bold text-body mb-1">Centro de Mando Mecánico</h4>
                    <p className="text-muted small mb-0">Gestión de tiempos y estados de las órdenes de trabajo.</p>
                </div>
                <Link href="/taller/ordenes/crear" className="btn btn-primary-accent fw-bold px-4 py-2 rounded-pill shadow-sm">
                    <i className="bi bi-plus-circle me-2"></i>Nueva Orden de Trabajo
                </Link>
            </div>

            <div className="row g-4 h-100">
                {/* Columna: PENDIENTES */}
                <div className="col-md-4">
                    <div className="glass-panel p-3" style={{ borderTop: '4px solid #64748b' }}>
                        <div className="d-flex justify-content-between align-items-center pb-3 mb-3 border-bottom border-secondary border-opacity-25">
                            <h6 className="text-body fw-bold mb-0 text-uppercase tracking-wider"><i className="bi bi-inbox text-muted me-2"></i>Pendientes</h6>
                            <span className="badge bg-secondary bg-opacity-50 rounded-pill px-2 py-1 border border-secondary">{pendientes.length}</span>
                        </div>
                        <div className="kanban-column custom-scrollbar" style={{ minHeight: '150px', maxHeight: '65vh', overflowY: 'auto', paddingRight: '5px' }}>
                            {pendientes.map(orden => (
                                <KanbanCard 
                                    key={orden.id} 
                                    orden={orden} 
                                    nextAction="" 
                                    nextLabel="" 
                                    nextColor=""
                                />
                            ))}
                            {pendientes.length === 0 && <p className="text-center text-muted small mt-4">No hay tareas pendientes.</p>}
                        </div>
                    </div>
                </div>

                {/* Columna: EN PROCESO */}
                <div className="col-md-4">
                    <div className="glass-panel p-3" style={{ borderTop: '4px solid var(--accent-primary)' }}>
                        <div className="d-flex justify-content-between align-items-center pb-3 mb-3 border-bottom border-secondary border-opacity-25">
                            <h6 className="fw-bold mb-0 text-uppercase tracking-wider text-primary-accent"><i className="bi bi-tools me-2"></i>En Proceso</h6>
                            <span className="badge bg-primary-accent rounded-pill px-2 py-1 text-white">{enProceso.length}</span>
                        </div>
                        <div className="kanban-column custom-scrollbar" style={{ minHeight: '150px', maxHeight: '65vh', overflowY: 'auto', paddingRight: '5px' }}>
                            {enProceso.map(orden => (
                                <KanbanCard 
                                    key={orden.id} 
                                    orden={orden} 
                                    nextAction="FINALIZADO" 
                                    nextLabel="Finalizar" 
                                    nextColor="success"
                                />
                            ))}
                            {enProceso.length === 0 && <p className="text-center text-muted small mt-4">El taller está libre.</p>}
                        </div>
                    </div>
                </div>

                {/* Columna: FINALIZADOS */}
                <div className="col-md-4">
                    <div className="glass-panel p-3" style={{ borderTop: '4px solid #10b981' }}>
                        <div className="d-flex justify-content-between align-items-center pb-3 mb-3 border-bottom border-secondary border-opacity-25">
                            <h6 className="fw-bold mb-0 text-uppercase tracking-wider text-success"><i className="bi bi-check-circle me-2"></i>Finalizados</h6>
                            <span className="badge bg-success bg-opacity-25 text-success border border-success rounded-pill px-2 py-1">{finalizados.length}</span>
                        </div>
                        <div className="kanban-column custom-scrollbar" style={{ minHeight: '150px', maxHeight: '65vh', overflowY: 'auto', paddingRight: '5px' }}>
                            {finalizados.map(orden => (
                                <KanbanCard 
                                    key={orden.id} 
                                    orden={orden} 
                                    nextAction="" 
                                    nextLabel="" 
                                    nextColor=""
                                />
                            ))}
                            {finalizados.length === 0 && <p className="text-center text-muted small mt-4">Sin trabajos finalizados.</p>}
                        </div>
                    </div>
                </div>
            </div>
        </>
    );

    return <AdminLayout>{content}</AdminLayout>;
}
