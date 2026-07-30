import React, { useState } from 'react';
import { Head, useForm } from '@inertiajs/react';
import AdminLayout from '../../Layouts/AdminLayout';

export default function Precios({ repuestos, flash }) {
    const [editingId, setEditingId] = useState(null);

    const { data, setData, post, processing } = useForm({
        id: '',
        costo: '',
        margen_ganancia: ''
    });

    const startEditing = (repuesto) => {
        setEditingId(repuesto.id);
        setData({
            id: repuesto.id,
            costo: repuesto.costo,
            margen_ganancia: repuesto.margen
        });
    };

    const saveChanges = (e) => {
        e.preventDefault();
        post('/inventario/precios/actualizar', {
            preserveScroll: true,
            onSuccess: () => setEditingId(null)
        });
    };

    const content = (
        <>
            <Head title="Gestión de Precios" />
            
            <div className="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 className="fw-bold text-white mb-1">Inventario y Precios</h4>
                    <p className="text-muted small mb-0">Gestión de costos y cálculo automático de precio de venta.</p>
                </div>
                <button className="btn btn-sm btn-primary-accent border-0 fw-semibold px-4">
                    <i className="bi bi-cloud-download me-2"></i> Exportar
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
                                <th className="bg-dark text-white text-uppercase" style={{width: '12%'}}>Código</th>
                                <th className="bg-dark text-white text-uppercase" style={{width: '28%'}}>Repuesto</th>
                                <th className="bg-dark text-white text-uppercase" style={{width: '15%'}}>Categoría</th>
                                <th className="bg-dark text-end text-white text-uppercase" style={{width: '8%'}}>Stock</th>
                                <th className="bg-dark text-end text-white text-uppercase" style={{width: '10%'}}>Costo ($)</th>
                                <th className="bg-dark text-end text-white text-uppercase" style={{width: '10%'}}>Margen (%)</th>
                                <th className="bg-dark text-end text-primary-accent text-uppercase" style={{width: '10%'}}>Venta ($)</th>
                                <th className="bg-dark text-center text-white text-uppercase" style={{width: '7%'}}>Editar</th>
                            </tr>
                        </thead>
                        <tbody className="align-middle">
                            {repuestos.map((r) => (
                                <tr key={r.id}>
                                    <td className="text-muted font-monospace small">{r.codigo}</td>
                                    <td className="fw-semibold text-white text-truncate" style={{maxWidth: '200px'}}>{r.nombre}</td>
                                    <td><span className="badge bg-secondary bg-opacity-25 text-white border border-secondary fw-normal">{r.categoria}</span></td>
                                    <td className={`text-end fw-bold ${r.stock <= 5 ? 'text-danger' : 'text-success'}`}>
                                        {r.stock}
                                    </td>
                                    
                                    {editingId === r.id ? (
                                        <>
                                            <td className="text-end">
                                                <input 
                                                    type="number" 
                                                    className="form-control form-control-sm text-end bg-dark border-primary-accent text-white"
                                                    value={data.costo}
                                                    onChange={e => setData('costo', e.target.value)}
                                                    style={{height: '28px', padding: '2px 5px', fontSize: '0.85rem'}}
                                                    autoFocus
                                                />
                                            </td>
                                            <td className="text-end">
                                                <input 
                                                    type="number" 
                                                    className="form-control form-control-sm text-end bg-dark border-primary-accent text-white"
                                                    value={data.margen_ganancia}
                                                    onChange={e => setData('margen_ganancia', e.target.value)}
                                                    style={{height: '28px', padding: '2px 5px', fontSize: '0.85rem'}}
                                                />
                                            </td>
                                            <td className="text-end text-muted">
                                                <em className="small">Calc...</em>
                                            </td>
                                            <td className="text-center">
                                                <button className="btn btn-sm btn-success p-0 px-2 me-1 border-0" onClick={saveChanges} disabled={processing}>
                                                    <i className="bi bi-check2"></i>
                                                </button>
                                                <button className="btn btn-sm btn-danger p-0 px-2 border-0" onClick={() => setEditingId(null)}>
                                                    <i className="bi bi-x"></i>
                                                </button>
                                            </td>
                                        </>
                                    ) : (
                                        <>
                                            <td className="text-end text-muted">{r.costo.toFixed(2)}</td>
                                            <td className="text-end text-muted">{r.margen}%</td>
                                            <td className="text-end fw-bold text-primary-accent">{r.precio_venta.toFixed(2)}</td>
                                            <td className="text-center">
                                                <button className="btn btn-sm btn-outline-secondary border-0 p-0 px-2 text-primary-accent hover-scale" onClick={() => startEditing(r)}>
                                                    <i className="bi bi-pencil-square"></i>
                                                </button>
                                            </td>
                                        </>
                                    )}
                                </tr>
                            ))}
                            {repuestos.length === 0 && (
                                <tr>
                                    <td colSpan="8" className="text-center py-5 text-muted">No hay repuestos registrados en el inventario.</td>
                                </tr>
                            )}
                        </tbody>
                    </table>
                </div>
            </div>
        </>
    );

    return <AdminLayout>{content}</AdminLayout>;
}
