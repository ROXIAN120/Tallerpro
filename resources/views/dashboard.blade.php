@extends('layouts.app')

@section('title', 'Resumen General')

@section('content')
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card h-100 bg-primary text-white">
            <div class="card-body p-4 d-flex flex-column justify-content-between">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="text-uppercase fw-semibold mb-0">Ingresos del Mes</h6>
                    <i class="bi bi-currency-dollar fs-2 opacity-50"></i>
                </div>
                <h2 class="display-5 fw-bold mb-0">$24,500</h2>
                <small class="text-white-50 mt-2">+12% vs el mes pasado</small>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card h-100 border-0">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="text-muted text-uppercase fw-semibold mb-0">Órdenes Activas</h6>
                    <div class="bg-light-warning text-warning rounded-circle p-2">
                        <i class="bi bi-tools fs-4"></i>
                    </div>
                </div>
                <h2 class="fw-bold mb-0 text-dark">18</h2>
                <small class="text-success mt-2"><i class="bi bi-arrow-up-right"></i> 5 en reparación</small>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card h-100 border-0">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="text-muted text-uppercase fw-semibold mb-0">Nuevos Clientes</h6>
                    <div class="bg-light-success text-success rounded-circle p-2">
                        <i class="bi bi-people fs-4"></i>
                    </div>
                </div>
                <h2 class="fw-bold mb-0 text-dark">42</h2>
                <small class="text-muted mt-2">En los últimos 30 días</small>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card h-100 border-0">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="text-muted text-uppercase fw-semibold mb-0">Alertas de Stock</h6>
                    <div class="bg-light-danger text-danger rounded-circle p-2">
                        <i class="bi bi-exclamation-triangle fs-4"></i>
                    </div>
                </div>
                <h2 class="fw-bold mb-0 text-dark">3</h2>
                <small class="text-danger mt-2">Repuestos por debajo del mínimo</small>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-8">
        <div class="card border-0 h-100">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-4">Últimas Órdenes de Trabajo</h5>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Orden #</th>
                                <th>Cliente</th>
                                <th>Vehículo</th>
                                <th>Fecha</th>
                                <th>Estado</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><span class="fw-semibold text-primary">OT-2024-001</span></td>
                                <td>
                                    <div class="fw-semibold text-dark">Juan Pérez</div>
                                    <small class="text-muted">juan@example.com</small>
                                </td>
                                <td>Toyota Corolla (2020)</td>
                                <td>Hoy, 10:30 AM</td>
                                <td><span class="badge bg-warning text-dark">En Reparación</span></td>
                                <td><button class="btn btn-sm btn-light"><i class="bi bi-eye"></i></button></td>
                            </tr>
                            <tr>
                                <td><span class="fw-semibold text-primary">OT-2024-002</span></td>
                                <td>
                                    <div class="fw-semibold text-dark">María Gómez</div>
                                    <small class="text-muted">77654321</small>
                                </td>
                                <td>Honda Civic (2018)</td>
                                <td>Hoy, 09:15 AM</td>
                                <td><span class="badge bg-secondary">Diagnóstico</span></td>
                                <td><button class="btn btn-sm btn-light"><i class="bi bi-eye"></i></button></td>
                            </tr>
                            <tr>
                                <td><span class="fw-semibold text-primary">OT-2024-003</span></td>
                                <td>
                                    <div class="fw-semibold text-dark">Carlos López</div>
                                    <small class="text-muted">carlos@test.com</small>
                                </td>
                                <td>Nissan Sentra (2022)</td>
                                <td>Ayer</td>
                                <td><span class="badge bg-success">Finalizado</span></td>
                                <td><button class="btn btn-sm btn-light"><i class="bi bi-eye"></i></button></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 h-100">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-4">Próximas Entregas</h5>
                <div class="d-flex align-items-center mb-4">
                    <div class="bg-primary text-white rounded p-3 me-3 text-center" style="width: 60px;">
                        <div class="fw-bold fs-5">29</div>
                        <div class="small">Jul</div>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1">Toyota Hilux (Placa: 4567-XYZ)</h6>
                        <div class="text-muted small">Cambio de Aceite y Filtros</div>
                    </div>
                </div>
                <div class="d-flex align-items-center">
                    <div class="bg-light text-dark rounded p-3 me-3 text-center border" style="width: 60px;">
                        <div class="fw-bold fs-5">30</div>
                        <div class="small">Jul</div>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1">Suzuki Swift (Placa: 1234-ABC)</h6>
                        <div class="text-muted small">Alineación y Balanceo</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
