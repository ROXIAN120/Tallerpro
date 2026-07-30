import React from 'react';
import { Head, useForm } from '@inertiajs/react';

export default function Login() {
    const { data, setData, post, processing, errors } = useForm({
        email: '',
        password: '',
    });

    const submit = (e) => {
        e.preventDefault();
        post('/login');
    };

    return (
        <div className="d-flex align-items-center justify-content-center min-vh-100">
            <Head title="Iniciar Sesión" />
            
            <div className="glass-panel p-5 w-100" style={{ maxWidth: '450px' }}>
                <div className="text-center mb-4">
                    <h3 className="fw-bold text-white mb-0">Taller Automotriz</h3>
                    <p className="text-muted small">Gestión Integral</p>
                </div>

                <form onSubmit={submit}>
                    <div className="mb-4">
                        <label className="form-label fw-semibold text-white">Correo Electrónico</label>
                        <input 
                            type="email" 
                            className={`form-control ${errors.email ? 'is-invalid' : ''}`}
                            value={data.email}
                            onChange={e => setData('email', e.target.value)}
                            placeholder="admin@admin.com"
                            autoFocus
                        />
                        {errors.email && <div className="invalid-feedback">{errors.email}</div>}
                    </div>

                    <div className="mb-4">
                        <label className="form-label fw-semibold text-white">Contraseña</label>
                        <input 
                            type="password" 
                            className="form-control"
                            value={data.password}
                            onChange={e => setData('password', e.target.value)}
                            placeholder="••••••••"
                        />
                    </div>

                    <button 
                        type="submit" 
                        className="btn bg-primary-accent text-white w-100 py-2 fw-semibold mt-3"
                        disabled={processing}
                        style={{ border: 'none', transition: 'all 0.3s' }}
                    >
                        {processing ? 'Iniciando sesión...' : 'Iniciar Sesión'}
                    </button>
                </form>
            </div>
        </div>
    );
}
