import React, { useState } from 'react';
import AdminLayout from '@/Layouts/AdminLayout';
import { Head, useForm, router, Link } from '@inertiajs/react';
import Swal from 'sweetalert2';

export default function InventarioIndex({ repuestos, categorias, proveedores, unidades = [] }) {
    const [searchTerm, setSearchTerm] = useState('');
    const [showModal, setShowModal] = useState(false);
    const [showSettingsModal, setShowSettingsModal] = useState(false);
    const [editingId, setEditingId] = useState(null);

    const { data, setData, post, put, delete: destroy, processing, errors, reset } = useForm({
        nombre: '',
        codigoBarras: '',
        descripcion: '',
        categoria_id: '',
        proveedor_id: '',
        costo: '',
        margen_ganancia: '',
        stock_inicial: 0,
        unidad_medida_id: ''
    });

    const { data: catData, setData: setCatData, post: postCat, put: putCat, delete: destroyCat, reset: resetCat } = useForm({ nombre: '' });
    const { data: uniData, setData: setUniData, post: postUni, put: putUni, delete: destroyUni, reset: resetUni } = useForm({ nombre: '', permite_fracciones: false });
    
    const [editingCatId, setEditingCatId] = useState(null);
    const [editingUniId, setEditingUniId] = useState(null);

    const filteredRepuestos = repuestos.filter(r => 
        r.nombre.toLowerCase().includes(searchTerm.toLowerCase()) || 
        r.codigoBarras.toLowerCase().includes(searchTerm.toLowerCase())
    );

    const handleSearch = (e) => setSearchTerm(e.target.value);

    const openCreateModal = () => {
        setEditingId(null);
        reset();
        setShowModal(true);
    };

    const openEditModal = (repuesto) => {
        setEditingId(repuesto.id);
        setData({
            nombre: repuesto.nombre,
            codigoBarras: repuesto.codigoBarras,
            descripcion: repuesto.descripcion || '',
            categoria_id: repuesto.categoria_id,
            proveedor_id: repuesto.proveedor_id,
            costo: repuesto.costo,
            margen_ganancia: repuesto.margen_ganancia,
            stock_inicial: repuesto.stockActual,
            unidad_medida_id: repuestos.find(r => r.id === repuesto.id)?.unidad_medida_id || unidades[0]?.id || ''
        });
        setShowModal(true);
    };

    const closeModal = () => {
        setShowModal(false);
        reset();
    };

    const handleSubmit = (e) => {
        e.preventDefault();
        if (editingId) {
            put(route('inventario.productos.update', editingId), {
                onSuccess: () => closeModal(),
            });
        } else {
            post(route('inventario.productos.store'), {
                onSuccess: () => closeModal(),
            });
        }
    };

    const handleDelete = (id) => {
        Swal.fire({
            title: '¿Estás seguro?',
            text: "Esta acción no se puede deshacer.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar',
            background: 'var(--bg-panel)',
            color: '#fff'
        }).then((result) => {
            if (result.isConfirmed) {
                destroy(route('inventario.productos.destroy', id), {
                    preserveScroll: true,
                    onError: (err) => {
                        if (err.error) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: err.error,
                                background: 'var(--bg-panel)',
                                color: '#fff'
                            });
                        }
                    }
                });
            }
        });
    };

    // --- Funciones para Categorías y Unidades ---
    const handleCatSubmit = (e) => {
        e.preventDefault();
        if(editingCatId) {
            putCat(route('inventario.categorias.update', editingCatId), { onSuccess: () => { setEditingCatId(null); resetCat(); } });
        } else {
            postCat(route('inventario.categorias.store'), { onSuccess: () => resetCat() });
        }
    };
    
    const handleDeleteCat = (id) => {
        if(confirm('¿Seguro que deseas eliminar esta categoría?')) destroyCat(route('inventario.categorias.destroy', id));
    };

    const handleUniSubmit = (e) => {
        e.preventDefault();
        if(editingUniId) {
            putUni(route('inventario.unidades.update', editingUniId), { onSuccess: () => { setEditingUniId(null); resetUni(); } });
        } else {
            postUni(route('inventario.unidades.store'), { onSuccess: () => resetUni() });
        }
    };

    const handleDeleteUni = (id) => {
        if(confirm('¿Seguro que deseas eliminar esta unidad de medida?')) destroyUni(route('inventario.unidades.destroy', id));
    };

    return (
        <AdminLayout>
            <Head title="Catálogo de Inventario | TallerPro" />

            <div className="container-fluid p-4">
                <div className="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h2 className="fw-bold mb-0 text-body fs-3">Catálogo Maestro</h2>
                        <p className="text-muted small mb-0 mt-1">Gestión centralizada de repuestos y productos</p>
                    </div>
                    <div className="d-flex gap-2">
                        <button onClick={() => setShowSettingsModal(true)} className="btn btn-outline-secondary fw-semibold rounded-3 shadow-sm d-flex align-items-center gap-2 hover-lift">
                            <i className="bi bi-gear"></i> Ajustes de Catálogo
                        </button>
                        <button onClick={openCreateModal} className="btn bg-primary-accent text-white fw-semibold rounded-3 shadow-sm d-flex align-items-center gap-2 hover-lift">
                            <i className="bi bi-plus-lg"></i> Nuevo Producto
                        </button>
                    </div>
                </div>

                <div className="card border-0 rounded-4 shadow-lg mb-4" style={{ backgroundColor: 'var(--bg-panel)' }}>
                    <div className="card-header bg-transparent border-0 p-4 pb-0 d-flex justify-content-between align-items-center">
                        <div className="position-relative" style={{ maxWidth: '300px', width: '100%' }}>
                            <i className="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                            <input 
                                type="text" 
                                className="form-control border-secondary ps-5 rounded-pill" 
                                placeholder="Buscar por código o nombre..." 
                                value={searchTerm}
                                onChange={handleSearch}
                            />
                        </div>
                    </div>
                    <div className="card-body p-0 mt-3">
                        <div className="table-responsive">
                            <table className="table table-hover align-middle mb-0 custom-table">
                                <thead style={{ backgroundColor: 'var(--bg-dark)' }}>
                                    <tr>
                                        <th className="px-4 text-uppercase text-muted small fw-semibold tracking-wider border-bottom border-secondary">Código</th>
                                        <th className="text-uppercase text-muted small fw-semibold tracking-wider border-bottom border-secondary">Producto</th>
                                        <th className="text-uppercase text-muted small fw-semibold tracking-wider border-bottom border-secondary">Categoría</th>
                                        <th className="text-uppercase text-muted small fw-semibold tracking-wider border-bottom border-secondary text-end">Costo</th>
                                        <th className="text-uppercase text-muted small fw-semibold tracking-wider border-bottom border-secondary text-end">Precio Venta</th>
                                        <th className="text-uppercase text-muted small fw-semibold tracking-wider border-bottom border-secondary text-center">Stock</th>
                                        <th className="px-4 text-uppercase text-muted small fw-semibold tracking-wider border-bottom border-secondary text-end">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {filteredRepuestos.length === 0 ? (
                                        <tr>
                                            <td colSpan="7" className="text-center py-5 text-muted">
                                                <i className="bi bi-inbox fs-1 d-block mb-3"></i>
                                                No se encontraron productos en el inventario.
                                            </td>
                                        </tr>
                                    ) : (
                                        filteredRepuestos.map((repuesto) => (
                                            <tr key={repuesto.id}>
                                                <td className="px-4">
                                                    <span className="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-50 font-monospace">
                                                        {repuesto.codigoBarras}
                                                    </span>
                                                </td>
                                                <td>
                                                    <div className="fw-bold text-body">{repuesto.nombre}</div>
                                                    <div className="small text-muted">{repuesto.proveedor}</div>
                                                </td>
                                                <td className="text-body">{repuesto.categoria}</td>
                                                <td className="text-end text-muted">Bs. {repuesto.costo.toFixed(2)}</td>
                                                <td className="text-end fw-semibold text-success">Bs. {repuesto.precio_venta.toFixed(2)}</td>
                                                <td className="text-center">
                                                    <span className={`badge ${repuesto.stockActual <= repuesto.stockMinimo ? 'bg-danger' : 'bg-primary-accent'} bg-opacity-25 ${repuesto.stockActual <= repuesto.stockMinimo ? 'text-danger' : 'text-primary-accent'} border ${repuesto.stockActual <= repuesto.stockMinimo ? 'border-danger' : 'border-primary-accent'} px-3 py-2 rounded-pill fw-bold`}>
                                                        {parseFloat(repuesto.stockActual).toFixed(2)} {repuesto.unidad_medida || 'Unidad'}
                                                    </span>
                                                </td>
                                                <td className="px-4 text-end">
                                                    <div className="btn-group">
                                                        <button 
                                                            onClick={() => openEditModal(repuesto)}
                                                            className="btn btn-sm btn-outline-secondary border-0 text-primary-accent hover-bg-light"
                                                            title="Editar Producto"
                                                        >
                                                            <i className="bi bi-pencil-square"></i>
                                                        </button>
                                                        <button 
                                                            onClick={() => handleDelete(repuesto.id)}
                                                            className="btn btn-sm btn-outline-secondary border-0 text-danger hover-bg-light"
                                                            title="Eliminar Producto"
                                                        >
                                                            <i className="bi bi-trash"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        ))
                                    )}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {/* Modal CRUD */}
            {showModal && (
                <>
                    <div className="modal-backdrop fade show" style={{ zIndex: 1040, backgroundColor: 'rgba(0,0,0,0.8)' }}></div>
                    <div className="modal fade show d-block" tabIndex="-1" style={{ zIndex: 1050 }}>
                        <div className="modal-dialog modal-dialog-centered modal-lg">
                            <div className="modal-content border-secondary border-opacity-25 shadow-lg rounded-4 bg-body">
                                <div className="modal-header border-bottom border-secondary border-opacity-25 p-4">
                                    <h5 className="modal-title fw-bold text-body">
                                        {editingId ? 'Editar Producto' : 'Registrar Nuevo Producto'}
                                    </h5>
                                    <button type="button" className="btn-close" onClick={closeModal}></button>
                                </div>
                                <form onSubmit={handleSubmit}>
                                    <div className="modal-body p-4">
                                        <div className="row g-4">
                                            <div className="col-md-4">
                                                <label className="form-label text-muted small fw-semibold tracking-wider">CÓDIGO / SKU</label>
                                                <input 
                                                    type="text" 
                                                    className={`form-control border-secondary ${errors.codigoBarras ? 'is-invalid' : ''}`}
                                                    value={data.codigoBarras} 
                                                    onChange={e => setData('codigoBarras', e.target.value)} 
                                                    required 
                                                />
                                                {errors.codigoBarras && <div className="invalid-feedback">{errors.codigoBarras}</div>}
                                            </div>
                                            <div className="col-md-8">
                                                <label className="form-label text-muted small fw-semibold tracking-wider">NOMBRE DEL PRODUCTO</label>
                                                <input 
                                                    type="text" 
                                                    className={`form-control border-secondary ${errors.nombre ? 'is-invalid' : ''}`}
                                                    value={data.nombre} 
                                                    onChange={e => setData('nombre', e.target.value)} 
                                                    required 
                                                />
                                                {errors.nombre && <div className="invalid-feedback">{errors.nombre}</div>}
                                            </div>
                                            <div className="col-md-6">
                                                <label className="form-label text-muted small fw-semibold tracking-wider">CATEGORÍA</label>
                                                <select 
                                                    className={`form-select border-secondary ${errors.categoria_id ? 'is-invalid' : ''}`}
                                                    value={data.categoria_id} 
                                                    onChange={e => setData('categoria_id', e.target.value)} 
                                                    required
                                                >
                                                    <option value="">Seleccione una categoría</option>
                                                    {categorias.map(c => (
                                                        <option key={c.id} value={c.id}>{c.nombre}</option>
                                                    ))}
                                                </select>
                                                {errors.categoria_id && <div className="invalid-feedback">{errors.categoria_id}</div>}
                                            </div>
                                            <div className="col-md-6">
                                                <label className="form-label text-muted small fw-semibold tracking-wider">PROVEEDOR</label>
                                                <select 
                                                    className={`form-select border-secondary ${errors.proveedor_id ? 'is-invalid' : ''}`}
                                                    value={data.proveedor_id} 
                                                    onChange={e => setData('proveedor_id', e.target.value)} 
                                                    required
                                                >
                                                    <option value="">Seleccione un proveedor</option>
                                                    {proveedores.map(p => (
                                                        <option key={p.id} value={p.id}>{p.nombre}</option>
                                                    ))}
                                                </select>
                                                {errors.proveedor_id && <div className="invalid-feedback">{errors.proveedor_id}</div>}
                                            </div>
                                            <div className="col-md-6">
                                                <label className="form-label text-muted small fw-semibold tracking-wider">UNIDAD DE MEDIDA</label>
                                                <select 
                                                    className={`form-select border-secondary ${errors.unidad_medida_id ? 'is-invalid' : ''}`}
                                                    value={data.unidad_medida_id} 
                                                    onChange={e => setData('unidad_medida_id', e.target.value)}
                                                    required
                                                >
                                                    <option value="">Seleccione una unidad</option>
                                                    {unidades.map(u => (
                                                        <option key={u.id} value={u.id}>{u.nombre}</option>
                                                    ))}
                                                </select>
                                                {errors.unidad_medida_id && <div className="invalid-feedback">{errors.unidad_medida_id}</div>}
                                            </div>
                                            <div className="col-md-6">
                                                <label className="form-label text-muted small fw-semibold tracking-wider">DESCRIPCIÓN (Opcional)</label>
                                                <textarea 
                                                    className="form-control border-secondary"
                                                    rows="1"
                                                    value={data.descripcion} 
                                                    onChange={e => setData('descripcion', e.target.value)} 
                                                ></textarea>
                                            </div>

                                            <div className="col-12"><hr className="border-secondary my-2" /></div>

                                            <div className="col-md-4">
                                                <label className="form-label text-muted small fw-semibold tracking-wider">COSTO BASE (Bs.)</label>
                                                <input 
                                                    type="number" 
                                                    step="0.01" 
                                                    min="0"
                                                    className={`form-control border-secondary ${errors.costo ? 'is-invalid' : ''}`}
                                                    value={data.costo} 
                                                    onChange={e => setData('costo', e.target.value)} 
                                                    required 
                                                />
                                                {errors.costo && <div className="invalid-feedback">{errors.costo}</div>}
                                            </div>
                                            <div className="col-md-4">
                                                <label className="form-label text-muted small fw-semibold tracking-wider">MARGEN GANANCIA (%)</label>
                                                <input 
                                                    type="number" 
                                                    step="0.01" 
                                                    min="0"
                                                    className={`form-control border-secondary ${errors.margen_ganancia ? 'is-invalid' : ''}`}
                                                    value={data.margen_ganancia} 
                                                    onChange={e => setData('margen_ganancia', e.target.value)} 
                                                    required 
                                                />
                                                {errors.margen_ganancia && <div className="invalid-feedback">{errors.margen_ganancia}</div>}
                                            </div>
                                            <div className="col-md-4">
                                                <label className="form-label text-muted small fw-semibold tracking-wider">
                                                    {editingId ? 'STOCK ACTUAL' : 'STOCK INICIAL'} {data.unidad_medida_id ? `(en ${unidades.find(u => u.id == data.unidad_medida_id)?.nombre || 'Unidades'})` : ''}
                                                </label>
                                                <input 
                                                    type="number" 
                                                    step="0.01"
                                                    min="0"
                                                    className={`form-control border-secondary ${errors.stock_inicial ? 'is-invalid' : ''}`}
                                                    value={data.stock_inicial} 
                                                    onChange={e => setData('stock_inicial', e.target.value)} 
                                                    required 
                                                    disabled={!!editingId} // No se puede editar el stock desde aquí si ya existe (usar kardex)
                                                />
                                                {errors.stock_inicial && <div className="invalid-feedback">{errors.stock_inicial}</div>}
                                                {!!editingId && <div className="form-text text-muted small">Use el Kardex para ajustar stock.</div>}
                                            </div>
                                        </div>
                                    </div>
                                    <div className="modal-footer border-top border-secondary border-opacity-25 p-4 rounded-bottom-4 bg-body-tertiary">
                                        <button type="button" className="btn btn-link text-muted text-decoration-none px-4" onClick={closeModal} disabled={processing}>
                                            Cancelar
                                        </button>
                                        <button type="submit" className="btn bg-primary-accent text-white px-4 fw-semibold shadow-sm d-flex align-items-center gap-2" disabled={processing}>
                                            {processing && <span className="spinner-border spinner-border-sm"></span>}
                                            <i className="bi bi-save"></i> Guardar Producto
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </>
            )}

            {/* Modal de Ajustes */}
            {showSettingsModal && (
                <>
                    <div className="modal-backdrop fade show" style={{ zIndex: 1040, backgroundColor: 'rgba(0,0,0,0.8)' }}></div>
                    <div className="modal fade show d-block" tabIndex="-1" style={{ zIndex: 1050 }}>
                        <div className="modal-dialog modal-dialog-centered modal-xl">
                            <div className="modal-content border-secondary border-opacity-25 shadow-lg rounded-4 bg-body">
                                <div className="modal-header border-bottom border-secondary border-opacity-25 p-4">
                                    <h5 className="modal-title fw-bold text-body">Ajustes de Catálogo</h5>
                                    <button type="button" className="btn-close" onClick={() => setShowSettingsModal(false)}></button>
                                </div>
                                <div className="modal-body p-4">
                                    <div className="row g-4">
                                        
                                        {/* Columna Categorías */}
                                        <div className="col-md-6 border-end border-secondary border-opacity-25 pr-4">
                                            <h6 className="fw-bold mb-3"><i className="bi bi-tags"></i> Categorías de Repuestos</h6>
                                            <form onSubmit={handleCatSubmit} className="d-flex gap-2 mb-3">
                                                <input 
                                                    type="text" 
                                                    className="form-control border-secondary form-control-sm" 
                                                    placeholder="Nueva Categoría" 
                                                    value={catData.nombre} 
                                                    onChange={e => setCatData('nombre', e.target.value)}
                                                    required 
                                                />
                                                <button type="submit" className="btn btn-sm bg-primary-accent text-white">
                                                    {editingCatId ? 'Guardar' : 'Añadir'}
                                                </button>
                                                {editingCatId && <button type="button" onClick={() => {setEditingCatId(null); resetCat()}} className="btn btn-sm btn-outline-secondary">X</button>}
                                            </form>
                                            
                                            <ul className="list-group list-group-flush border border-secondary border-opacity-25 rounded" style={{maxHeight: '300px', overflowY: 'auto'}}>
                                                {categorias.map(c => (
                                                    <li key={c.id} className="list-group-item bg-transparent d-flex justify-content-between align-items-center py-2">
                                                        <span>{c.nombre}</span>
                                                        <div className="btn-group">
                                                            <button onClick={() => {setEditingCatId(c.id); setCatData('nombre', c.nombre)}} className="btn btn-sm text-primary p-1"><i className="bi bi-pencil"></i></button>
                                                            <button onClick={() => handleDeleteCat(c.id)} className="btn btn-sm text-danger p-1"><i className="bi bi-trash"></i></button>
                                                        </div>
                                                    </li>
                                                ))}
                                            </ul>
                                        </div>

                                        {/* Columna Unidades */}
                                        <div className="col-md-6 pl-4">
                                            <h6 className="fw-bold mb-3"><i className="bi bi-rulers"></i> Unidades de Medida</h6>
                                            <form onSubmit={handleUniSubmit} className="mb-3 p-3 border border-secondary border-opacity-25 rounded">
                                                <div className="row g-2 align-items-end">
                                                    <div className="col-7">
                                                        <input 
                                                            type="text" 
                                                            className="form-control border-secondary form-control-sm" 
                                                            placeholder="Ej: Litro, Par, Tambor" 
                                                            value={uniData.nombre} 
                                                            onChange={e => setUniData('nombre', e.target.value)}
                                                            required 
                                                        />
                                                    </div>
                                                    <div className="col-12 mt-2">
                                                        <div className="form-check form-switch">
                                                            <input 
                                                                className="form-check-input" 
                                                                type="checkbox" 
                                                                role="switch" 
                                                                id="swFracciones"
                                                                checked={uniData.permite_fracciones}
                                                                onChange={e => setUniData('permite_fracciones', e.target.checked)}
                                                            />
                                                            <label className="form-check-label small" htmlFor="swFracciones">Permite fracciones y porcentajes (Ej: Vender 30% del producto)</label>
                                                        </div>
                                                    </div>
                                                    <div className="col-12 mt-2 text-end">
                                                        {editingUniId && <button type="button" onClick={() => {setEditingUniId(null); resetUni()}} className="btn btn-sm btn-outline-secondary me-2">Cancelar</button>}
                                                        <button type="submit" className="btn btn-sm bg-primary-accent text-white">
                                                            {editingUniId ? 'Guardar Cambios' : 'Añadir Unidad'}
                                                        </button>
                                                    </div>
                                                </div>
                                            </form>

                                            <ul className="list-group list-group-flush border border-secondary border-opacity-25 rounded" style={{maxHeight: '300px', overflowY: 'auto'}}>
                                                {unidades.map(u => (
                                                    <li key={u.id} className="list-group-item bg-transparent d-flex justify-content-between align-items-center py-2">
                                                        <div>
                                                            <span className="fw-semibold d-block">{u.nombre}</span>
                                                            {u.permite_fracciones ? 
                                                                <span className="badge bg-success bg-opacity-25 text-success rounded-pill" style={{fontSize: '0.65rem'}}>Fraccionable</span> 
                                                                : <span className="badge bg-secondary bg-opacity-25 text-secondary rounded-pill" style={{fontSize: '0.65rem'}}>Entero</span>
                                                            }
                                                        </div>
                                                        <div className="btn-group">
                                                            <button onClick={() => {setEditingUniId(u.id); setUniData({nombre: u.nombre, permite_fracciones: u.permite_fracciones})}} className="btn btn-sm text-primary p-1"><i className="bi bi-pencil"></i></button>
                                                            <button onClick={() => handleDeleteUni(u.id)} className="btn btn-sm text-danger p-1"><i className="bi bi-trash"></i></button>
                                                        </div>
                                                    </li>
                                                ))}
                                            </ul>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </>
            )}

            <style>{`
                .tracking-wider { letter-spacing: 0.05em; }
                .custom-table th { font-size: 0.75rem; padding-top: 1rem; padding-bottom: 1rem; }
                .custom-table td { padding-top: 1rem; padding-bottom: 1rem; }
                .hover-bg-light:hover { background-color: var(--bs-tertiary-bg, #f8f9fa) !important; }
                .hover-lift { transition: transform 0.2s ease, box-shadow 0.2s ease; }
                .hover-lift:hover { transform: translateY(-2px); box-shadow: 0 4px 15px rgba(0,0,0,0.2); }
            `}</style>
        </AdminLayout>
    );
}
