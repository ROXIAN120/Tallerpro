import React, { useState } from 'react';
import { Head, useForm, Link } from '@inertiajs/react';
import AdminLayout from '../../Layouts/AdminLayout';

export default function Servicios({ categorias, servicios, flash }) {
    const [view, setView] = useState('servicios'); // 'servicios' or 'categorias'

    // Forms
    const formCat = useForm({ nombre: '' });
    const formServ = useForm({
        nombre: '',
        categoria_servicio_id: '',
        descripcion: '',
        precioBase: '',
        tiempoEstimadoHoras: ''
    });

    const [editingId, setEditingId] = useState(null);

    const submitCategoria = (e) => {
        e.preventDefault();
        if (editingId) {
            formCat.put(`/taller/servicios/categorias/${editingId}`, {
                onSuccess: () => { formCat.reset(); setEditingId(null); }
            });
        } else {
            formCat.post('/taller/servicios/categorias', {
                onSuccess: () => formCat.reset()
            });
        }
    };

    const submitServicio = (e) => {
        e.preventDefault();
        if (editingId) {
            formServ.put(`/taller/servicios/${editingId}`, {
                onSuccess: () => { formServ.reset(); setEditingId(null); }
            });
        } else {
            formServ.post('/taller/servicios', {
                onSuccess: () => formServ.reset()
            });
        }
    };

    const editCat = (cat) => {
        setEditingId(cat.id);
        formCat.setData({ nombre: cat.nombre });
    };

    const editServ = (serv) => {
        setEditingId(serv.id);
        formServ.setData({
            nombre: serv.nombre,
            categoria_servicio_id: serv.categoria_servicio_id,
            descripcion: serv.descripcion || '',
            precioBase: serv.precioBase,
            tiempoEstimadoHoras: serv.tiempoEstimadoHoras
        });
    };

    const cancelEdit = () => {
        setEditingId(null);
        formCat.reset();
        formServ.reset();
    };

    const content = (
        <>
            <Head title="Catálogo de Servicios" />
            
            <div className="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 className="fw-bold text-body mb-1">Mano de Obra (Servicios)</h4>
                    <p className="text-muted small mb-0">Gestión de servicios ofrecidos y sus tarifas.</p>
                </div>
                <div className="btn-group">
                    <button 
                        className={`btn ${view === 'servicios' ? 'btn-primary-accent' : 'btn-outline-secondary text-body'}`}
                        onClick={() => { setView('servicios'); cancelEdit(); }}
                    >
                        Ver Servicios
                    </button>
                    <button 
                        className={`btn ${view === 'categorias' ? 'btn-primary-accent' : 'btn-outline-secondary text-body'}`}
                        onClick={() => { setView('categorias'); cancelEdit(); }}
                    >
                        Ver Categorías
                    </button>
                </div>
            </div>

            {flash?.success && (
                <div className="alert alert-success bg-success bg-opacity-25 text-success border-0 rounded-3 mb-4">
                    <i className="bi bi-check-circle-fill me-2"></i>{flash.success}
                </div>
            )}
            
            {flash?.error && (
                <div className="alert alert-danger bg-danger bg-opacity-25 text-danger border-0 rounded-3 mb-4">
                    <i className="bi bi-exclamation-triangle-fill me-2"></i>{flash.error}
                </div>
            )}

            <div className="row g-4">
                {/* Formulario Lateral */}
                <div className="col-lg-4">
                    <div className="glass-panel p-4 bg-body">
                        <h6 className="text-primary-accent fw-bold mb-4 border-bottom border-secondary border-opacity-25 pb-2">
                            {editingId ? `Editar ${view === 'servicios' ? 'Servicio' : 'Categoría'}` : `Nuevo ${view === 'servicios' ? 'Servicio' : 'Categoría'}`}
                        </h6>

                        {view === 'categorias' ? (
                            <form onSubmit={submitCategoria}>
                                <div className="mb-3">
                                    <label className="form-label small text-muted">Nombre de la Categoría</label>
                                    <input 
                                        type="text" 
                                        className="form-control bg-body text-body"
                                        value={formCat.data.nombre}
                                        onChange={e => formCat.setData('nombre', e.target.value)}
                                        required
                                    />
                                </div>
                                <div className="d-flex gap-2">
                                    <button type="submit" className="btn btn-primary-accent w-100 fw-bold" disabled={formCat.processing}>
                                        {formCat.processing ? 'Guardando...' : 'Guardar Categoría'}
                                    </button>
                                    {editingId && (
                                        <button type="button" className="btn btn-secondary w-50" onClick={cancelEdit}>Cancelar</button>
                                    )}
                                </div>
                            </form>
                        ) : (
                            <form onSubmit={submitServicio}>
                                <div className="mb-3">
                                    <label className="form-label small text-muted">Nombre del Servicio</label>
                                    <input 
                                        type="text" 
                                        className="form-control bg-body text-body"
                                        value={formServ.data.nombre}
                                        onChange={e => formServ.setData('nombre', e.target.value)}
                                        required
                                    />
                                </div>
                                <div className="mb-3">
                                    <label className="form-label small text-muted">Categoría</label>
                                    <select 
                                        className="form-select bg-body text-body"
                                        value={formServ.data.categoria_servicio_id}
                                        onChange={e => formServ.setData('categoria_servicio_id', e.target.value)}
                                        required
                                    >
                                        <option value="">Seleccione...</option>
                                        {categorias.map(c => <option key={c.id} value={c.id}>{c.nombre}</option>)}
                                    </select>
                                </div>
                                <div className="mb-3">
                                    <label className="form-label small text-muted">Precio Base (Bs.)</label>
                                    <input 
                                        type="number" 
                                        step="0.01"
                                        className="form-control bg-body text-body"
                                        value={formServ.data.precioBase}
                                        onChange={e => formServ.setData('precioBase', e.target.value)}
                                        required
                                    />
                                </div>
                                <div className="mb-3">
                                    <label className="form-label small text-muted">Tiempo Estimado</label>
                                    <input 
                                        type="text" 
                                        placeholder="ej: 1.5 horas, 30 min, Indefinido"
                                        className="form-control bg-body text-body"
                                        value={formServ.data.tiempoEstimadoHoras}
                                        onChange={e => formServ.setData('tiempoEstimadoHoras', e.target.value)}
                                        required
                                    />
                                </div>
                                <div className="d-flex gap-2">
                                    <button type="submit" className="btn btn-primary-accent w-100 fw-bold" disabled={formServ.processing}>
                                        {formServ.processing ? 'Guardando...' : 'Guardar Servicio'}
                                    </button>
                                    {editingId && (
                                        <button type="button" className="btn btn-secondary w-50" onClick={cancelEdit}>Cancelar</button>
                                    )}
                                </div>
                            </form>
                        )}
                    </div>
                </div>

                {/* Tabla de Registros */}
                <div className="col-lg-8">
                    <div className="glass-panel p-0 overflow-hidden bg-body">
                        <div className="table-responsive p-3">
                            {view === 'categorias' ? (
                                <table className="table table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th className="bg-dark text-white">Categoría</th>
                                            <th className="bg-dark text-center text-white">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody className="align-middle">
                                        {categorias.map(cat => (
                                            <tr key={cat.id}>
                                                <td className="text-body fw-semibold">{cat.nombre}</td>
                                                <td className="text-center">
                                                    <button onClick={() => editCat(cat)} className="btn btn-sm btn-outline-secondary border-0 p-0 px-2 text-primary-accent mx-1"><i className="bi bi-pencil-square"></i></button>
                                                    <Link href={`/taller/servicios/categorias/${cat.id}`} method="delete" as="button" className="btn btn-sm btn-outline-danger border-0 p-0 px-2 mx-1"><i className="bi bi-trash"></i></Link>
                                                </td>
                                            </tr>
                                        ))}
                                    </tbody>
                                </table>
                            ) : (
                                <table className="table table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th className="bg-dark text-white">Servicio</th>
                                            <th className="bg-dark text-white">Categoría</th>
                                            <th className="bg-dark text-end text-white">Precio Base</th>
                                            <th className="bg-dark text-center text-white">Tiempo</th>
                                            <th className="bg-dark text-center text-white">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody className="align-middle">
                                        {servicios.map(serv => (
                                            <tr key={serv.id}>
                                                <td className="text-body fw-semibold">{serv.nombre}</td>
                                                <td><span className="badge bg-secondary">{serv.categoria?.nombre}</span></td>
                                                <td className="text-end fw-bold text-success">Bs. {parseFloat(serv.precioBase).toFixed(2)}</td>
                                                <td className="text-center text-muted">{serv.tiempoEstimadoHoras}</td>
                                                <td className="text-center">
                                                    <button onClick={() => editServ(serv)} className="btn btn-sm btn-outline-secondary border-0 p-0 px-2 text-primary-accent mx-1"><i className="bi bi-pencil-square"></i></button>
                                                    <Link href={`/taller/servicios/${serv.id}`} method="delete" as="button" className="btn btn-sm btn-outline-danger border-0 p-0 px-2 mx-1"><i className="bi bi-trash"></i></Link>
                                                </td>
                                            </tr>
                                        ))}
                                    </tbody>
                                </table>
                            )}
                        </div>
                    </div>
                </div>
            </div>
        </>
    );

    return <AdminLayout>{content}</AdminLayout>;
}
