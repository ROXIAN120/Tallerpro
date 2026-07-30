import React, { useState } from 'react';
import { Head, useForm } from '@inertiajs/react';
import AdminLayout from '../../Layouts/AdminLayout';

export default function Kardex({ movimientos, repuestos, flash }) {
    const { data, setData, post, processing, reset, errors } = useForm({
        repuesto_id: '',
        tipo: 'ENTRADA',
        cantidad: '',
        motivo: 'Compra de inventario',
    });

    const [showModal, setShowModal] = useState(false);

    const submit = (e) => {
        e.preventDefault();
        post('/inventario/kardex/movimiento', {
            onSuccess: () => {
                setShowModal(false);
                reset();
            }
        });
    };

    const content = (
        <>
            <Head title="Kardex de Inventario" />
            
            <div className="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 className="fw-bold text-white mb-1">Trazabilidad (Kardex)</h4>
                    <p className="text-muted small mb-0">Registro inmutable de movimientos de inventario.</p>
                </div>
                <button onClick={() => setShowModal(true)} className="btn btn-primary-accent fw-bold px-4 py-2 rounded shadow-sm">
                    <i className="bi bi-box-arrow-in-down me-2"></i>Registrar Entrada
                </button>
            </div>

            {flash && flash.success && (
                <div className="alert bg-success bg-opacity-25 border border-success text-success py-2 px-3 small rounded-3 mb-4 d-flex align-items-center">
                    <i className="bi bi-check-circle-fill me-2 fs-5"></i> {flash.success}
                </div>
            )}

            <div className="glass-panel p-0 overflow-hidden">
                <div className="table-responsive p-3">
                    <table className="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th className="bg-dark text-white text-uppercase" style={{width: '12%'}}>Fecha</th>
                                <th className="bg-dark text-white text-uppercase" style={{width: '12%'}}>Código</th>
                                <th className="bg-dark text-white text-uppercase" style={{width: '30%'}}>Repuesto</th>
                                <th className="bg-dark text-white text-uppercase" style={{width: '11%'}}>Tipo</th>
                                <th className="bg-dark text-end text-white text-uppercase" style={{width: '10%'}}>Cantidad</th>
                                <th className="bg-dark text-white text-uppercase" style={{width: '15%'}}>Motivo</th>
                                <th className="bg-dark text-white text-uppercase" style={{width: '10%'}}>Usuario</th>
                            </tr>
                        </thead>
                        <tbody className="align-middle">
                            {movimientos.map((m) => (
                                <tr key={m.id}>
                                    <td className="text-muted small"><i className="bi bi-calendar-event me-1"></i> {m.fecha}</td>
                                    <td className="text-muted font-monospace small">{m.codigo}</td>
                                    <td className="fw-semibold text-white">{m.repuesto}</td>
                                    <td>
                                        <span className={`badge border ${m.tipo === 'ENTRADA' ? 'bg-success bg-opacity-25 text-success border-success' : 'bg-danger bg-opacity-25 text-danger border-danger'} fw-normal`}>
                                            {m.tipo === 'ENTRADA' ? (
                                                <><i className="bi bi-arrow-down-left-circle-fill me-1"></i> ENTRADA</>
                                            ) : (
                                                <><i className="bi bi-arrow-up-right-circle-fill me-1"></i> SALIDA</>
                                            )}
                                        </span>
                                    </td>
                                    <td className={`text-end fw-bold fs-6 ${m.tipo === 'ENTRADA' ? 'text-success' : 'text-danger'}`}>
                                        {m.tipo === 'ENTRADA' ? '+' : '-'}{m.cantidad}
                                    </td>
                                    <td className="text-truncate text-muted small" style={{maxWidth: '150px'}} title={m.motivo}>{m.motivo}</td>
                                    <td><span className="badge bg-secondary bg-opacity-25 text-white border border-secondary fw-normal"><i className="bi bi-person-fill me-1"></i> {m.usuario}</span></td>
                                </tr>
                            ))}
                            {movimientos.length === 0 && (
                                <tr>
                                    <td colSpan="7" className="text-center py-5 text-muted">
                                        <i className="bi bi-inbox fs-2 d-block mb-2"></i>
                                        No existen movimientos en el historial del Kardex.
                                    </td>
                                </tr>
                            )}
                        </tbody>
                    </table>
                </div>
            </div>

        </>
    );

    return (
        <AdminLayout>
            {content}

            {/* Modal for New Movement */}
            {showModal && (
                <div className="modal fade show d-block" tabIndex="-1" style={{backgroundColor: 'rgba(0,0,0,0.5)'}}>
                    <div className="modal-dialog modal-dialog-centered">
                        <div className="modal-content bg-dark text-white border-secondary">
                            <form onSubmit={submit}>
                                <div className="modal-header border-secondary border-opacity-25">
                                    <h5 className="modal-title fw-bold text-primary-accent">
                                        <i className="bi bi-box-arrow-in-down me-2"></i>Ingreso de Mercadería
                                    </h5>
                                    <button type="button" className="btn-close btn-close-white" onClick={() => setShowModal(false)}></button>
                                </div>
                                <div className="modal-body">
                                    <div className="mb-3">
                                        <label className="form-label text-muted small">Repuesto / Producto</label>
                                        <select 
                                            className="form-select bg-dark text-white border-secondary"
                                            value={data.repuesto_id}
                                            onChange={e => setData('repuesto_id', e.target.value)}
                                            required
                                        >
                                            <option value="">Seleccione un repuesto...</option>
                                            {repuestos.map(r => (
                                                <option key={r.id} value={r.id}>{r.nombre} ({r.codigoBarras})</option>
                                            ))}
                                        </select>
                                        {errors.repuesto_id && <div className="text-danger small mt-1">{errors.repuesto_id}</div>}
                                    </div>
                                    <div className="row mb-3">
                                        <div className="col-md-6">
                                            {(() => {
                                                const selectedRep = repuestos.find(r => r.id == data.repuesto_id);
                                                const isFractionable = selectedRep && selectedRep.unidad_medida && selectedRep.unidad_medida.permite_fracciones;
                                                return (
                                                    <>
                                                        <label className="form-label text-muted small">Cantidad {isFractionable ? '(Soporta decimales)' : ''}</label>
                                                        <input 
                                                            type="number" 
                                                            step={isFractionable ? "0.01" : "1"}
                                                            className="form-control bg-dark text-white border-secondary"
                                                            value={data.cantidad}
                                                            onChange={e => setData('cantidad', e.target.value)}
                                                            min={isFractionable ? "0.01" : "1"}
                                                            required
                                                            disabled={!data.repuesto_id}
                                                        />
                                                    </>
                                                );
                                            })()}
                                            {errors.cantidad && <div className="text-danger small mt-1">{errors.cantidad}</div>}
                                        </div>
                                        <div className="col-md-6">
                                            <label className="form-label text-muted small">Tipo de Movimiento</label>
                                            <input type="text" className="form-control bg-dark text-white border-secondary" value="ENTRADA" readOnly />
                                        </div>
                                    </div>
                                    <div className="mb-3">
                                        <label className="form-label text-muted small">Motivo</label>
                                        <input 
                                            type="text" 
                                            className="form-control bg-dark text-white border-secondary"
                                            value={data.motivo}
                                            onChange={e => setData('motivo', e.target.value)}
                                            required
                                        />
                                        {errors.motivo && <div className="text-danger small mt-1">{errors.motivo}</div>}
                                    </div>
                                </div>
                                <div className="modal-footer border-secondary border-opacity-25">
                                    <button type="button" className="btn btn-outline-secondary" onClick={() => setShowModal(false)}>Cancelar</button>
                                    <button type="submit" className="btn btn-primary-accent fw-bold" disabled={processing}>
                                        {processing ? 'Registrando...' : 'Registrar Entrada'}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            )}
        </AdminLayout>
    );
}
