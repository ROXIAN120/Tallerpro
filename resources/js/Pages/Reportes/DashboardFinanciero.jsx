import React, { useState } from 'react';
import { Head, router } from '@inertiajs/react';
import AdminLayout from '../../Layouts/AdminLayout';

export default function DashboardFinanciero({ metricas, filtros }) {
    const [exportando, setExportando] = useState(false);
    const [fechas, setFechas] = useState({
        fecha_inicio: filtros?.fecha_inicio || '',
        fecha_fin: filtros?.fecha_fin || ''
    });

    const handleExportarExcel = () => {
        if (exportando) return;
        setExportando(true);
        window.location.href = `/reportes/exportar-excel?fecha_inicio=${fechas.fecha_inicio}&fecha_fin=${fechas.fecha_fin}`;
        setTimeout(() => setExportando(false), 2000);
    };

    const aplicarFiltros = () => {
        router.get('/reportes/dashboard', fechas, { preserveState: true });
    };

    const limpiarFiltros = () => {
        setFechas({ fecha_inicio: '', fecha_fin: '' });
        router.get('/reportes/dashboard', {}, { preserveState: true });
    };

    const content = (
        <>
            <Head title="Dashboard Financiero" />
            
            <div className="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 className="fw-bold text-white mb-1">Rentabilidad y Finanzas</h4>
                    <p className="text-muted small mb-0">Análisis financiero de las Órdenes de Trabajo.</p>
                </div>
                <div className="d-flex gap-2">
                    <button 
                        className="btn btn-primary-accent fw-semibold border-0 shadow-sm px-4 py-2 d-flex align-items-center"
                        onClick={handleExportarExcel}
                        disabled={exportando}
                    >
                        {exportando ? (
                            <><span className="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Generando Excel...</>
                        ) : (
                            <><i className="bi bi-file-earmark-excel-fill me-2 fs-5"></i> Exportar a Excel</>
                        )}
                    </button>
                </div>
            </div>

            {/* Filtros de Fecha */}
            <div className="glass-panel p-3 mb-4 d-flex align-items-end gap-3 flex-wrap">
                <div>
                    <label className="text-muted small mb-1">Fecha Inicio</label>
                    <input 
                        type="date" 
                        className="form-control bg-dark text-white border-secondary"
                        value={fechas.fecha_inicio}
                        onChange={e => setFechas({...fechas, fecha_inicio: e.target.value})}
                    />
                </div>
                <div>
                    <label className="text-muted small mb-1">Fecha Fin</label>
                    <input 
                        type="date" 
                        className="form-control bg-dark text-white border-secondary"
                        value={fechas.fecha_fin}
                        onChange={e => setFechas({...fechas, fecha_fin: e.target.value})}
                    />
                </div>
                <div className="d-flex gap-2">
                    <button className="btn btn-outline-info" onClick={aplicarFiltros}>
                        <i className="bi bi-search me-2"></i>Filtrar
                    </button>
                    {(fechas.fecha_inicio || fechas.fecha_fin) && (
                        <button className="btn btn-outline-danger" onClick={limpiarFiltros}>
                            <i className="bi bi-x-circle me-1"></i>
                        </button>
                    )}
                </div>
            </div>

            {/* Tarjetas KPI de Alta Densidad */}
            <div className="row g-4 mb-5">
                <div className="col-md-3">
                    <div className="glass-panel p-4 text-center h-100 position-relative overflow-hidden" style={{ borderTop: '4px solid var(--accent-primary)' }}>
                        <h6 className="text-muted text-uppercase fw-bold mb-1 small tracking-wider">Total Ingresos</h6>
                        <span className="d-block text-muted small mb-3" style={{ fontSize: '0.7rem' }}>Lo que pagaron los clientes</span>
                        <h3 className="text-body fw-bold mb-0 text-primary-accent">
                            Bs. {metricas.ingresos.toLocaleString()}
                        </h3>
                        <i className="bi bi-graph-up position-absolute" style={{ fontSize: '6rem', right: '-15px', bottom: '-20px', color: 'var(--accent-primary)', opacity: 0.1 }}></i>
                    </div>
                </div>
                
                <div className="col-md-3">
                    <div className="glass-panel p-4 text-center h-100 position-relative overflow-hidden" style={{ borderTop: '4px solid #ef4444' }}>
                        <h6 className="text-muted text-uppercase fw-bold mb-1 small tracking-wider">Costos Operativos</h6>
                        <span className="d-block text-muted small mb-3" style={{ fontSize: '0.7rem' }}>Costo interno de los repuestos usados</span>
                        <h3 className="text-body fw-bold mb-0 text-danger">
                            Bs. {metricas.costos.toLocaleString()}
                        </h3>
                        <i className="bi bi-graph-down position-absolute text-danger" style={{ fontSize: '6rem', right: '-15px', bottom: '-20px', opacity: 0.1 }}></i>
                    </div>
                </div>
                
                <div className="col-md-3">
                    <div className="glass-panel p-4 text-center h-100 position-relative overflow-hidden" style={{ borderTop: '4px solid #10b981' }}>
                        <h6 className="text-muted text-uppercase fw-bold mb-1 small tracking-wider">Utilidad Neta</h6>
                        <span className="d-block text-muted small mb-3" style={{ fontSize: '0.7rem' }}>Dinero libre (ganancia real)</span>
                        <h3 className="text-body fw-bold mb-0 text-success">
                            Bs. {metricas.utilidad.toLocaleString()}
                        </h3>
                        <i className="bi bi-cash-stack position-absolute text-success" style={{ fontSize: '6rem', right: '-15px', bottom: '-20px', opacity: 0.1 }}></i>
                    </div>
                </div>
                
                <div className="col-md-3">
                    <div className="glass-panel p-4 text-center h-100 position-relative overflow-hidden" style={{ borderTop: '4px solid #f59e0b' }}>
                        <h6 className="text-muted text-uppercase fw-bold mb-1 small tracking-wider">Margen Global</h6>
                        <span className="d-block text-muted small mb-3" style={{ fontSize: '0.7rem' }}>Porcentaje de rentabilidad</span>
                        <h3 className="text-body fw-bold mb-0 text-warning">
                            {metricas.margen}%
                        </h3>
                        <i className="bi bi-pie-chart position-absolute text-warning" style={{ fontSize: '6rem', right: '-15px', bottom: '-20px', opacity: 0.1 }}></i>
                    </div>
                </div>
            </div>

            {/* Tabla de Resumen (Alta Densidad) */}
            <div className="row g-4">
                <div className="col-lg-12">
                    <div className="glass-panel p-0 overflow-hidden">
                        <div className="p-3 px-4 border-bottom border-secondary border-opacity-25 bg-secondary bg-opacity-10">
                            <h6 className="text-body fw-bold mb-0"><i className="bi bi-receipt me-2 text-primary-accent"></i>Desglose de Operaciones (Total: {metricas.total_ordenes} Órdenes Finalizadas)</h6>
                        </div>
                        <div className="table-responsive p-3">
                            <table className="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th className="bg-dark text-white text-uppercase" style={{width: '25%'}}>Concepto Contable</th>
                                        <th className="bg-dark text-center text-white text-uppercase" style={{width: '15%'}}>Volumen</th>
                                        <th className="bg-dark text-end text-white text-uppercase" style={{width: '20%'}}>Promedio por Orden (Bs.)</th>
                                        <th className="bg-dark text-end text-white text-uppercase" style={{width: '20%'}}>Subtotal Acumulado (Bs.)</th>
                                        <th className="bg-dark text-center text-white text-uppercase" style={{width: '20%'}}>Impacto en Utilidad</th>
                                    </tr>
                                </thead>
                                <tbody className="align-middle">
                                    <tr>
                                        <td>
                                            <div className="text-body fw-bold">1. Ingresos Brutos (Ventas)</div>
                                            <small className="text-muted d-block">Suma total de lo cobrado a los clientes por servicios y piezas.</small>
                                        </td>
                                        <td className="text-center fw-bold text-body">{metricas.total_ordenes}</td>
                                        <td className="text-end text-muted">
                                            {(metricas.total_ordenes > 0 ? metricas.ingresos / metricas.total_ordenes : 0).toLocaleString()}
                                        </td>
                                        <td className="text-end fw-bold text-success">+{metricas.ingresos.toLocaleString()}</td>
                                        <td className="text-center"><span className="badge rounded-pill bg-success text-white shadow-sm px-3 py-2" style={{ fontSize: '0.8rem', letterSpacing: '0.5px' }}><i className="bi bi-arrow-up-right me-2 fw-bold"></i>Positivo</span></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div className="text-body fw-bold">2. Costo de Materiales</div>
                                            <small className="text-muted d-block">Valor de compra (costo interno) de las piezas utilizadas en esas órdenes.</small>
                                        </td>
                                        <td className="text-center fw-bold text-body">-</td>
                                        <td className="text-end text-muted">
                                            {(metricas.total_ordenes > 0 ? metricas.costos / metricas.total_ordenes : 0).toLocaleString()}
                                        </td>
                                        <td className="text-end fw-bold text-danger">-{metricas.costos.toLocaleString()}</td>
                                        <td className="text-center"><span className="badge rounded-pill bg-danger text-white shadow-sm px-3 py-2" style={{ fontSize: '0.8rem', letterSpacing: '0.5px' }}><i className="bi bi-arrow-down-right me-2 fw-bold"></i>Negativo</span></td>
                                    </tr>
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
