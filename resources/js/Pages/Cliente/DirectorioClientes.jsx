import React, { useState } from 'react';
import { Head, Link, router } from '@inertiajs/react';
import AdminLayout from '../../Layouts/AdminLayout';

export default function DirectorioClientes({ vehiculos }) {
    const [searchTerm, setSearchTerm] = useState('');

    const filteredVehiculos = vehiculos.filter(vehiculo => {
        const searchLower = searchTerm.toLowerCase();
        return (
            vehiculo.placa.toLowerCase().includes(searchLower) ||
            vehiculo.cliente.toLowerCase().includes(searchLower) ||
            vehiculo.telefono.toLowerCase().includes(searchLower)
        );
    });

    const goToHistory = (placa) => {
        router.get(`/clientes/vehiculo/${placa}/historial`);
    };

    const content = (
        <>
            <Head title="Directorio de Clientes" />

            <div className="d-flex justify-content-between align-items-center mb-5">
                <div>
                    <h3 className="fw-bold text-white mb-1">Directorio de Clientes</h3>
                    <p className="text-muted small mb-0">Buscador y expedientes de todos los vehículos.</p>
                </div>
            </div>

            {/* Buscador */}
            <div className="glass-panel p-4 mb-5">
                <div className="input-group input-group-lg">
                    <span className="input-group-text bg-dark border-secondary border-opacity-25 text-primary-accent" id="search-addon">
                        <i className="bi bi-search"></i>
                    </span>
                    <input 
                        type="text" 
                        className="form-control bg-dark border-secondary border-opacity-25 text-white shadow-none" 
                        placeholder="Buscar por placa, nombre de cliente o teléfono..." 
                        aria-label="Buscar" 
                        aria-describedby="search-addon"
                        value={searchTerm}
                        onChange={(e) => setSearchTerm(e.target.value)}
                    />
                </div>
            </div>

            {/* Grid de Tarjetas */}
            <div className="row g-4">
                {filteredVehiculos.length === 0 ? (
                    <div className="col-12 text-center py-5">
                        <i className="bi bi-emoji-frown fs-1 text-muted mb-3 d-block"></i>
                        <h5 className="text-white">No se encontraron resultados</h5>
                        <p className="text-muted">Intenta con otro término de búsqueda.</p>
                    </div>
                ) : (
                    filteredVehiculos.map(vehiculo => (
                        <div key={vehiculo.id} className="col-md-4 col-lg-3">
                            <div 
                                className="glass-panel p-0 h-100 overflow-hidden" 
                                style={{ transition: 'transform 0.2s', cursor: 'pointer' }} 
                                onMouseOver={(e) => e.currentTarget.style.transform = 'translateY(-5px)'} 
                                onMouseOut={(e) => e.currentTarget.style.transform = 'translateY(0)'}
                                onClick={() => goToHistory(vehiculo.placa)}
                            >
                                <div className="p-4 border-bottom border-secondary border-opacity-25 d-flex justify-content-between align-items-center bg-secondary bg-opacity-10">
                                    <h5 className="fw-bold text-white mb-0">{vehiculo.placa}</h5>
                                    <span className="badge bg-primary-accent bg-opacity-25 text-primary-accent border border-primary-accent border-opacity-50 px-2 py-1">
                                        <i className="bi bi-car-front me-1"></i>{vehiculo.anio}
                                    </span>
                                </div>
                                <div className="p-4 d-flex flex-column justify-content-between h-100">
                                    <div>
                                        <div className="mb-3">
                                            <div className="text-muted small mb-1 text-uppercase fw-bold tracking-wider">Cliente</div>
                                            <div className="text-white fw-semibold d-flex align-items-center">
                                                <i className="bi bi-person-fill me-2 text-muted"></i>
                                                {vehiculo.cliente}
                                            </div>
                                        </div>
                                        <div className="mb-4">
                                            <div className="text-muted small mb-1 text-uppercase fw-bold tracking-wider">Contacto</div>
                                            <div className="text-white d-flex align-items-center">
                                                <i className="bi bi-telephone-fill me-2 text-muted"></i>
                                                {vehiculo.telefono}
                                            </div>
                                        </div>
                                    </div>
                                    <div className="d-grid mt-2">
                                        <Link href={`/clientes/vehiculo/${vehiculo.placa}/historial`} className="btn btn-outline-info w-100 fw-semibold">
                                            <i className="bi bi-clock-history me-2"></i>Ver Expediente
                                        </Link>
                                    </div>
                                </div>
                            </div>
                        </div>
                    ))
                )}
            </div>
        </>
    );

    return <AdminLayout>{content}</AdminLayout>;
}
