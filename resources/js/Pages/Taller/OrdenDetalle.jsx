import React from 'react';
import { Head, useForm, Link, router } from '@inertiajs/react';
import AdminLayout from '../../Layouts/AdminLayout';

export default function OrdenDetalle({ orden, repuestosStock, serviciosCatalogo, flash }) {
    // Form for Repuestos
    const { data: dataRepuesto, setData: setDataRepuesto, post: postRepuesto, processing: procRepuesto, reset: resetRepuesto } = useForm({
        repuesto_id: '',
        cantidad: 1,
        precio_final: ''
    });

    const { data: dataServicio, setData: setDataServicio, post: postServicio, processing: procServicio, reset: resetServicio } = useForm({
        servicio_id: '',
        precio_ajustado: ''
    });

    const submitRepuesto = (e) => {
        e.preventDefault();
        postRepuesto(`/taller/ordenes/${orden.id}/repuestos`, {
            preserveScroll: true,
            onSuccess: () => resetRepuesto()
        });
    };

    const submitServicio = (e) => {
        e.preventDefault();
        postServicio(`/taller/ordenes/${orden.id}/servicios`, {
            preserveScroll: true,
            onSuccess: () => resetServicio()
        });
    };

    const iniciarTrabajo = () => {
        router.post('/taller/kanban/estado', {
            id: orden.id,
            estado: 'EN PROCESO'
        }, { 
            preserveScroll: true,
            preserveState: true
        });
    };

    const eliminarServicio = (detalleId) => {
        if(confirm('¿Seguro que quieres eliminar este servicio?')) {
            router.delete(`/taller/ordenes/${orden.id}/servicios/${detalleId}`, { preserveScroll: true });
        }
    };

    const eliminarRepuesto = (detalleId, repuestoId) => {
        if(confirm('¿Seguro que quieres devolver este repuesto al inventario?')) {
            router.delete(`/taller/ordenes/${orden.id}/repuestos/${detalleId}/${repuestoId}`, { preserveScroll: true });
        }
    };

    // Calculate totals
    let totalServicios = 0;
    let totalRepuestos = 0;

    orden.detalles.forEach(d => {
        if (d.servicio) {
            totalServicios += parseFloat(d.subtotal);
        }
        if (d.repuestos) {
            d.repuestos.forEach(r => {
                totalRepuestos += (parseFloat(r.pivot.precioVenta) * r.pivot.cantidad);
            });
        }
    });
    
    const granTotal = totalServicios + totalRepuestos;

    const content = (
        <>
            <Head title={`Orden de Trabajo #${orden.id}`} />
            
            <div className="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 className="fw-bold text-body mb-1">
                        <Link href="/taller/kanban" className="text-muted me-2"><i className="bi bi-arrow-left"></i></Link>
                        Orden de Trabajo OT-{orden.id}
                    </h4>
                    <p className="text-muted small mb-0">{orden.vehiculo?.placa} - {orden.cliente?.nombre}</p>
                </div>
                <div className="d-flex gap-2">
                    <span className={`badge ${orden.estado === 'FINALIZADO' ? 'bg-success' : 'bg-primary-accent'} px-3 py-2 d-flex align-items-center`}>
                        ESTADO: {orden.estado}
                    </span>
                    {orden.estado === 'FINALIZADO' && (
                        <a href={`/reportes/orden/${orden.id}/pdf`} target="_blank" className="btn btn-outline-secondary d-flex align-items-center bg-body text-body" rel="noreferrer">
                            <i className="bi bi-printer me-2"></i> Imprimir Factura
                        </a>
                    )}
                </div>
            </div>

            {flash?.success && (
                <div className="alert alert-success bg-success bg-opacity-25 text-success border-0 rounded-3 mb-4">
                    <i className="bi bi-check-circle-fill me-2"></i>{flash.success}
                </div>
            )}

            <div className="row g-4">
                {/* Panel Izquierdo: Información, Servicios y Repuestos */}
                <div className="col-lg-8">
                    <div className="glass-panel p-4 mb-4 bg-body">
                        <h6 className="text-primary-accent fw-bold mb-3 border-bottom border-secondary border-opacity-25 pb-2">Diagnóstico General</h6>
                        <p className="text-muted small">{orden.diagnostico || 'Sin diagnóstico registrado'}</p>
                    </div>

                    <div className="glass-panel p-4 mb-4 bg-body">
                        <h6 className="text-primary-accent fw-bold mb-3 border-bottom border-secondary border-opacity-25 pb-2">
                            <i className="bi bi-wrench me-2"></i>Mano de Obra (Servicios)
                        </h6>
                        
                        {orden.detalles && orden.detalles.filter(d => d.servicio).length > 0 ? (
                            <div className="table-responsive">
                                <table className="table table-dense text-body mb-0">
                                    <thead>
                                        <tr>
                                            <th>Servicio</th>
                                            <th>Categoría</th>
                                            <th className="text-end">Subtotal</th>
                                            <th className="text-center" style={{ width: '50px' }}>Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {orden.detalles.filter(d => d.servicio).map((d, idx) => (
                                            <tr key={idx}>
                                                <td className="fw-semibold">{d.servicio.nombre}</td>
                                                <td><span className="badge bg-secondary">{d.servicio.categoria?.nombre || 'General'}</span></td>
                                                <td className="text-end fw-bold text-body">Bs. {parseFloat(d.subtotal).toFixed(2)}</td>
                                                <td className="text-center">
                                                    {orden.estado !== 'FINALIZADO' && (
                                                        <button onClick={() => eliminarServicio(d.id)} className="btn btn-sm btn-outline-danger py-0 px-2" title="Eliminar servicio">
                                                            <i className="bi bi-trash"></i>
                                                        </button>
                                                    )}
                                                </td>
                                            </tr>
                                        ))}
                                    </tbody>
                                </table>
                            </div>
                        ) : (
                            <p className="text-muted small text-center my-4">No se han registrado servicios de mano de obra en esta orden.</p>
                        )}
                    </div>

                    <div className="glass-panel p-4 bg-body">
                        <h6 className="text-primary-accent fw-bold mb-3 border-bottom border-secondary border-opacity-25 pb-2">
                            <i className="bi bi-box-seam me-2"></i>Repuestos Utilizados
                        </h6>
                        
                        <div className="table-responsive">
                            <table className="table table-dense text-body mb-0">
                                <thead>
                                    <tr>
                                        <th>Repuesto</th>
                                        <th className="text-center">Cant.</th>
                                        <th className="text-end">P. Unit.</th>
                                        <th className="text-end">Subtotal</th>
                                        <th className="text-center" style={{ width: '50px' }}>Acción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {orden.detalles.map(d => 
                                        d.repuestos && d.repuestos.map((rep, idx) => (
                                            <tr key={`${d.id}-${idx}`}>
                                                <td className="fw-semibold">{rep.nombre} <small className="text-muted ms-1">({rep.unidad_medida || 'Unidad'})</small></td>
                                                <td className="text-center">{rep.pivot.cantidad}</td>
                                                <td className="text-end">Bs. {parseFloat(rep.pivot.precioVenta).toFixed(2)}</td>
                                                <td className="text-end fw-bold text-body">Bs. {(rep.pivot.cantidad * rep.pivot.precioVenta).toFixed(2)}</td>
                                                <td className="text-center">
                                                    {orden.estado !== 'FINALIZADO' && (
                                                        <button onClick={() => eliminarRepuesto(d.id, rep.id)} className="btn btn-sm btn-outline-danger py-0 px-2" title="Devolver repuesto">
                                                            <i className="bi bi-x-circle"></i>
                                                        </button>
                                                    )}
                                                </td>
                                            </tr>
                                        ))
                                    )}
                                    {totalRepuestos === 0 && (
                                        <tr>
                                            <td colSpan="4" className="text-muted small text-center my-4">No se han registrado repuestos.</td>
                                        </tr>
                                    )}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {/* Panel Derecho: Agregar Items y Total */}
                <div className="col-lg-4">
                    {/* Botón para Iniciar si está PENDIENTE */}
                    {orden.estado === 'PENDIENTE' && (
                        <div className="glass-panel p-4 mb-4 bg-body text-center" style={{ borderTop: '4px solid var(--accent-primary)' }}>
                            <h6 className="fw-bold mb-3 text-body">¿Listo para comenzar a registrar?</h6>
                            <button onClick={iniciarTrabajo} className="btn btn-primary-accent fw-bold w-100 py-2 rounded shadow-sm">
                                <i className="bi bi-tools me-2"></i>Iniciar Trabajo (En Proceso)
                            </button>
                        </div>
                    )}

                    {/* Solo mostrar form si NO está finalizado */}
                    {orden.estado !== 'FINALIZADO' && (
                        <>
                            <div className="glass-panel p-4 mb-4 bg-body" style={{ borderTop: '3px solid var(--accent-primary)' }}>
                                <h6 className="text-body fw-bold mb-3"><i className="bi bi-wrench me-2 text-primary-accent"></i>Añadir Mano de Obra</h6>
                                <form onSubmit={submitServicio}>
                                    <div className="mb-3">
                                        <label className="form-label small text-muted">Seleccionar Servicio</label>
                                        <select 
                                            className="form-select bg-body text-body border-secondary mb-2"
                                            value={dataServicio.servicio_id}
                                            onChange={e => {
                                                const id = e.target.value;
                                                setDataServicio(data => {
                                                    const srv = serviciosCatalogo.find(s => s.id == id);
                                                    return { ...data, servicio_id: id, precio_ajustado: srv ? srv.precioBase : '' };
                                                });
                                            }}
                                            required
                                        >
                                            <option value="">-- Catálogo de Servicios --</option>
                                            {serviciosCatalogo.map(s => (
                                                <option key={s.id} value={s.id}>{s.nombre} - Base: Bs. {s.precioBase}</option>
                                            ))}
                                        </select>
                                        {dataServicio.servicio_id && (
                                            <>
                                                <label className="form-label small text-muted">Precio Final para este Vehículo (Bs.)</label>
                                                <input 
                                                    type="number" 
                                                    step="0.01" 
                                                    min="0"
                                                    className="form-control bg-body text-body border-secondary" 
                                                    value={dataServicio.precio_ajustado} 
                                                    onChange={e => setDataServicio('precio_ajustado', e.target.value)}
                                                    required 
                                                    placeholder="Ej: 150.00"
                                                />
                                                <small className="text-muted d-block mt-1 mb-2">Ajuste el precio según el tipo y estado del vehículo.</small>
                                            </>
                                        )}
                                    </div>
                                    <button type="submit" className="btn btn-outline-primary w-100 fw-bold" disabled={procServicio}>
                                        {procServicio ? 'Agregando...' : 'Agregar Servicio'}
                                    </button>
                                </form>
                            </div>

                            <div className="glass-panel p-4 mb-4 bg-body" style={{ borderTop: '3px solid var(--accent-primary)' }}>
                                <h6 className="text-body fw-bold mb-3"><i className="bi bi-box-seam me-2 text-primary-accent"></i>Asignar Repuesto</h6>
                                <form onSubmit={submitRepuesto}>
                                    <div className="mb-3">
                                        <label className="form-label small text-muted">Seleccionar Repuesto</label>
                                        <select 
                                            className="form-select bg-body text-body border-secondary mb-2"
                                            value={dataRepuesto.repuesto_id}
                                            onChange={e => {
                                                const id = e.target.value;
                                                setDataRepuesto(data => {
                                                    const rep = repuestosStock.find(r => r.id == id);
                                                    let precioSugerido = '';
                                                    if (rep) {
                                                        const costo = parseFloat(rep.costo || 0);
                                                        const margen = parseFloat(rep.margen_ganancia || 0);
                                                        precioSugerido = (costo + (costo * (margen / 100))).toFixed(2);
                                                    }
                                                    return { ...data, repuesto_id: id, precio_final: precioSugerido };
                                                });
                                            }}
                                            required
                                        >
                                            <option value="">-- Buscar en Stock Kardex --</option>
                                            {repuestosStock.map(r => (
                                                <option key={r.id} value={r.id} disabled={r.stockActual <= 0}>
                                                    {r.nombre} (Disp: {parseFloat(r.stockActual).toFixed(2)} {r.unidad_medida?.nombre || 'Unidad'}) {r.stockActual <= 0 ? '- AGOTADO' : ''}
                                                </option>
                                            ))}
                                        </select>
                                        {dataRepuesto.repuesto_id && (() => {
                                            const selectedRep = repuestosStock.find(r => r.id == dataRepuesto.repuesto_id);
                                            const isFractionable = selectedRep && selectedRep.unidad_medida && selectedRep.unidad_medida.permite_fracciones;
                                            
                                            const qty = parseFloat(dataRepuesto.cantidad) || 0;
                                            const entero = Math.floor(qty);
                                            const fraccion = Math.round((qty - entero) * 100);

                                            return (
                                                <>
                                                    <div className="mb-4 mt-3">
                                                        {isFractionable ? (
                                                            <div className="row g-2">
                                                                <div className="col-6">
                                                                    <label className="form-label small text-muted">
                                                                        {selectedRep.unidad_medida?.nombre ? `${selectedRep.unidad_medida.nombre}s Completos` : 'Unidades Completas'}
                                                                    </label>
                                                                    <input 
                                                                        type="number" 
                                                                        className="form-control bg-body text-body border-secondary"
                                                                        value={entero}
                                                                        onChange={e => {
                                                                            const val = parseInt(e.target.value || 0);
                                                                            setDataRepuesto('cantidad', val + (fraccion / 100));
                                                                        }}
                                                                        min="0"
                                                                    />
                                                                </div>
                                                                <div className="col-6">
                                                                    <label className="form-label small text-muted">
                                                                        Porcentaje de {selectedRep.unidad_medida?.nombre || 'Unidad'}
                                                                    </label>
                                                                    <select 
                                                                        className="form-select bg-body text-body border-secondary"
                                                                        value={fraccion}
                                                                        onChange={e => {
                                                                            const val = parseInt(e.target.value || 0);
                                                                            setDataRepuesto('cantidad', entero + (val / 100));
                                                                        }}
                                                                    >
                                                                        <option value="0">0%</option>
                                                                        <option value="10">10%</option>
                                                                        <option value="20">20%</option>
                                                                        <option value="25">25% (Cuarto)</option>
                                                                        <option value="30">30%</option>
                                                                        <option value="40">40%</option>
                                                                        <option value="50">50% (Medio)</option>
                                                                        <option value="60">60%</option>
                                                                        <option value="70">70%</option>
                                                                        <option value="75">75%</option>
                                                                        <option value="80">80%</option>
                                                                        <option value="90">90%</option>
                                                                    </select>
                                                                </div>
                                                                {qty <= 0 && <small className="text-danger d-block w-100 mt-1">Debe especificar una cantidad mayor a 0.</small>}
                                                            </div>
                                                        ) : (
                                                            <>
                                                                <label className="form-label small text-muted">
                                                                    Cantidad a usar (Solo unidades enteras)
                                                                </label>
                                                                <input 
                                                                    type="number" 
                                                                    step="1"
                                                                    className="form-control bg-body text-body border-secondary"
                                                                    value={dataRepuesto.cantidad}
                                                                    onChange={e => setDataRepuesto('cantidad', e.target.value)}
                                                                    min="1"
                                                                    required
                                                                />
                                                            </>
                                                        )}
                                                    </div>
                                                    <label className="form-label small text-muted">
                                                        Precio Unitario (por {selectedRep.unidad_medida?.nombre || 'Unidad'})
                                                    </label>
                                                    <input 
                                                        type="number" 
                                                        step="0.01" 
                                                        min="0"
                                                        className="form-control bg-body text-body border-secondary mb-2" 
                                                        value={dataRepuesto.precio_final} 
                                                        onChange={e => setDataRepuesto('precio_final', e.target.value)}
                                                        required 
                                                        placeholder="Precio unitario..."
                                                    />
                                                    <div className="alert bg-success bg-opacity-10 text-success border border-success border-opacity-25 mt-2 mb-2 py-2 px-3 small rounded-2 d-flex justify-content-between align-items-center">
                                                        <span>Subtotal a sumar a la orden:</span>
                                                        <span className="fw-bold fs-6">Bs. {((parseFloat(dataRepuesto.cantidad) || 0) * (parseFloat(dataRepuesto.precio_final) || 0)).toFixed(2)}</span>
                                                    </div>
                                                    <small className="text-muted d-block mb-3">
                                                        Ajuste el precio si requiere igualar a la competencia o según el vehículo.
                                                    </small>
                                                </>
                                            );
                                        })()}
                                    </div>
                                    <button type="submit" className="btn btn-primary-accent w-100 fw-bold" disabled={procRepuesto || !dataRepuesto.repuesto_id || parseFloat(dataRepuesto.cantidad) <= 0}>
                                        {procRepuesto ? 'Procesando...' : 'Descontar y Agregar'}
                                    </button>
                                </form>
                            </div>
                        </>
                    )}

                    <div className="glass-panel p-4 bg-body">
                        <h6 className="text-body fw-bold mb-3 border-bottom border-secondary border-opacity-25 pb-2">Resumen Financiero (Factura)</h6>
                        <div className="d-flex justify-content-between mb-2">
                            <span className="text-muted small">Total Mano de Obra:</span>
                            <span className="text-body fw-semibold">Bs. {totalServicios.toFixed(2)}</span>
                        </div>
                        <div className="d-flex justify-content-between mb-3 border-bottom border-secondary border-opacity-25 pb-3">
                            <span className="text-muted small">Total Repuestos:</span>
                            <span className="text-body fw-semibold">Bs. {totalRepuestos.toFixed(2)}</span>
                        </div>
                        <div className="d-flex justify-content-between align-items-center mt-3">
                            <span className="text-body fw-bold fs-5">GRAN TOTAL:</span>
                            <h3 className="text-success fw-bold mb-0">Bs. {granTotal.toFixed(2)}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </>
    );

    return <AdminLayout>{content}</AdminLayout>;
}
