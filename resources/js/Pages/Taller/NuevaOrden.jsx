import React, { useState } from 'react';
import { Head, useForm, Link } from '@inertiajs/react';
import AdminLayout from '../../Layouts/AdminLayout';

export default function NuevaOrden() {
    const { data, setData, post, processing, errors } = useForm({
        cliente_nombre: '',
        cliente_telefono: '',
        cliente_email: '',
        vehiculo_placa: '',
        vehiculo_marca: '',
        vehiculo_modelo: '',
        diagnostico: '',
    });

    const [vehiculoEncontrado, setVehiculoEncontrado] = useState(false);
    const [buscando, setBuscando] = useState(false);

    const checkPlaca = async (placa) => {
        if (placa.length < 5) {
            setVehiculoEncontrado(false);
            return;
        }
        setBuscando(true);
        try {
            const response = await fetch(`/api/vehiculos/${placa}`);
            if (response.ok) {
                const resData = await response.json();
                if (resData.encontrado) {
                    setData(prev => ({
                        ...prev,
                        vehiculo_marca: resData.vehiculo_marca,
                        vehiculo_modelo: resData.vehiculo_modelo,
                        cliente_nombre: resData.cliente_nombre,
                        cliente_telefono: resData.cliente_telefono,
                        cliente_email: resData.cliente_email
                    }));
                    setVehiculoEncontrado(true);
                } else {
                    setVehiculoEncontrado(false);
                }
            } else {
                setVehiculoEncontrado(false);
            }
        } catch (error) {
            setVehiculoEncontrado(false);
        } finally {
            setBuscando(false);
        }
    };

    const submit = (e) => {
        e.preventDefault();
        post('/taller/ordenes/guardar');
    };

    const content = (
        <>
            <Head title="Nueva Orden de Trabajo" />
            
            <div className="d-flex justify-content-between align-items-center mb-5">
                <div>
                    <h4 className="fw-bold text-white mb-1"><Link href="/taller/kanban" className="text-muted me-2"><i className="bi bi-arrow-left"></i></Link>Recepción de Vehículo</h4>
                    <p className="text-muted small mb-0">Creación de una nueva orden de trabajo e ingreso al taller.</p>
                </div>
            </div>

            <div className="row justify-content-center">
                <div className="col-lg-8">
                    <form onSubmit={submit}>
                        
                        <div className="glass-panel p-4 mb-4">
                            <h6 className="text-primary-accent fw-bold mb-3 border-bottom border-secondary border-opacity-25 pb-2"><i className="bi bi-person-vcard me-2"></i>Datos del Cliente</h6>
                            <div className="row g-3">
                                <div className="col-md-6">
                                    <label className="form-label text-muted small fw-semibold">Nombre Completo *</label>
                                    <input 
                                        type="text" 
                                        className={`form-control bg-dark text-white border-secondary ${errors.cliente_nombre ? 'is-invalid' : ''}`}
                                        value={data.cliente_nombre}
                                        onChange={e => setData('cliente_nombre', e.target.value)}
                                        placeholder="Ej: Juan Pérez"
                                        required
                                    />
                                    {errors.cliente_nombre && <div className="invalid-feedback">{errors.cliente_nombre}</div>}
                                </div>
                                <div className="col-md-6">
                                    <label className="form-label text-muted small fw-semibold">Teléfono (WhatsApp) *</label>
                                    <div className="input-group">
                                        <span className="input-group-text bg-dark text-muted border-secondary border-end-0">+591</span>
                                        <input 
                                            type="text" 
                                            className={`form-control bg-dark text-white border-secondary border-start-0 ${errors.cliente_telefono ? 'is-invalid' : ''}`}
                                            value={data.cliente_telefono}
                                            onChange={e => setData('cliente_telefono', e.target.value)}
                                            placeholder="Ej: 71234567"
                                            required
                                        />
                                    </div>
                                    {errors.cliente_telefono && <div className="text-danger small mt-1">{errors.cliente_telefono}</div>}
                                </div>
                                <div className="col-md-12">
                                    <label className="form-label text-muted small fw-semibold">Correo Electrónico</label>
                                    <input 
                                        type="email" 
                                        className="form-control bg-dark text-white border-secondary"
                                        value={data.cliente_email}
                                        onChange={e => setData('cliente_email', e.target.value)}
                                        placeholder="Ej: juan@correo.com"
                                    />
                                </div>
                            </div>
                        </div>

                        <div className="glass-panel p-4 mb-4">
                            <h6 className="text-primary-accent fw-bold mb-3 border-bottom border-secondary border-opacity-25 pb-2"><i className="bi bi-car-front me-2"></i>Datos del Vehículo</h6>
                            <div className="row g-3">
                                <div className="col-md-4">
                                    <label className="form-label text-muted small fw-semibold">Placa (Matrícula) *</label>
                                    <input 
                                        type="text" 
                                        className={`form-control bg-dark text-white border-secondary text-uppercase ${errors.vehiculo_placa ? 'is-invalid' : ''}`}
                                        value={data.vehiculo_placa}
                                        onChange={e => setData('vehiculo_placa', e.target.value.toUpperCase())}
                                        onBlur={e => checkPlaca(e.target.value.toUpperCase())}
                                        placeholder="Ej: ABC-1234"
                                        required
                                    />
                                    {buscando && <div className="text-info small mt-1"><span className="spinner-border spinner-border-sm me-1"></span>Buscando...</div>}
                                    {errors.vehiculo_placa && <div className="invalid-feedback">{errors.vehiculo_placa}</div>}
                                </div>
                                {vehiculoEncontrado && (
                                    <div className="col-md-12 mt-2">
                                        <div className="alert alert-success bg-success bg-opacity-10 border border-success border-opacity-25 text-white p-3 mb-0 d-flex align-items-center">
                                            <i className="bi bi-check-circle-fill fs-4 text-success me-3"></i>
                                            <div>
                                                <strong className="d-block mb-1">Vehículo encontrado en el sistema</strong>
                                                <span className="small">Se han autocompletado los datos. Si el conductor actual es distinto al dueño registrado, simplemente modifica el nombre y teléfono arriba.</span>
                                            </div>
                                        </div>
                                    </div>
                                )}
                                <div className="col-md-4">
                                    <label className="form-label text-muted small fw-semibold">Marca *</label>
                                    <input 
                                        type="text" 
                                        className={`form-control bg-dark text-white border-secondary ${errors.vehiculo_marca ? 'is-invalid' : ''}`}
                                        value={data.vehiculo_marca}
                                        onChange={e => setData('vehiculo_marca', e.target.value)}
                                        placeholder="Ej: Toyota"
                                        required
                                    />
                                    {errors.vehiculo_marca && <div className="invalid-feedback">{errors.vehiculo_marca}</div>}
                                </div>
                                <div className="col-md-4">
                                    <label className="form-label text-muted small fw-semibold">Modelo / Año</label>
                                    <input 
                                        type="text" 
                                        className="form-control bg-dark text-white border-secondary"
                                        value={data.vehiculo_modelo}
                                        onChange={e => setData('vehiculo_modelo', e.target.value)}
                                        placeholder="Ej: Corolla 2020"
                                    />
                                </div>
                                <div className="col-md-12 mt-4">
                                    <label className="form-label text-muted small fw-semibold">Diagnóstico Inicial o Problema Reportado</label>
                                    <textarea 
                                        className="form-control bg-dark text-white border-secondary"
                                        value={data.diagnostico}
                                        onChange={e => setData('diagnostico', e.target.value)}
                                        rows="3"
                                        placeholder="Describa el motivo del ingreso al taller..."
                                    ></textarea>
                                </div>
                            </div>
                        </div>

                        <div className="d-flex justify-content-end gap-3 mt-4">
                            <Link href="/taller/kanban" className="btn btn-outline-secondary border-0 px-4">Cancelar</Link>
                            <button type="submit" className="btn btn-primary-accent px-5 fw-bold" disabled={processing}>
                                {processing ? 'Guardando...' : 'Crear Orden de Trabajo'}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </>
    );

    return <AdminLayout>{content}</AdminLayout>;
}
