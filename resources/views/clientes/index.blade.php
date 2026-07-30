@extends('layouts.app')

@section('title', 'Gestión de Clientes')

@section('content')
<div class="card border-0">
    <div class="card-header bg-white border-0 pt-4 pb-0 d-flex justify-content-between align-items-center">
        <h5 class="fw-bold mb-0">Directorio de Clientes</h5>
        <button class="btn btn-primary px-4"><i class="bi bi-plus-lg me-2"></i> Nuevo Cliente</button>
    </div>
    <div class="card-body p-4">
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" class="form-control bg-light border-start-0" placeholder="Buscar cliente por nombre o CI...">
                </div>
            </div>
            <div class="col-md-8 text-end">
                <button class="btn btn-light text-muted me-2"><i class="bi bi-filter"></i> Filtros</button>
                <button class="btn btn-light text-muted"><i class="bi bi-cloud-download"></i> Exportar</button>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Cliente</th>
                        <th>Carnet de Identidad</th>
                        <th>Teléfono</th>
                        <th>Vehículos</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><span class="text-muted">#1024</span></td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="bg-light-primary text-primary rounded-circle d-flex justify-content-center align-items-center fw-bold me-3" style="width: 40px; height: 40px; background-color: #e1f0ff;">
                                    JP
                                </div>
                                <div>
                                    <div class="fw-semibold text-dark">Juan Pérez</div>
                                    <small class="text-muted">juan@example.com</small>
                                </div>
                            </div>
                        </td>
                        <td>88776655 SC</td>
                        <td>+591 77665544</td>
                        <td><span class="badge bg-secondary">2 Registrados</span></td>
                        <td>
                            <button class="btn btn-sm btn-light me-1"><i class="bi bi-pencil"></i></button>
                            <button class="btn btn-sm btn-light text-danger"><i class="bi bi-trash"></i></button>
                        </td>
                    </tr>
                    <tr>
                        <td><span class="text-muted">#1025</span></td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="bg-light-warning text-warning rounded-circle d-flex justify-content-center align-items-center fw-bold me-3" style="width: 40px; height: 40px; background-color: #fff4de;">
                                    MG
                                </div>
                                <div>
                                    <div class="fw-semibold text-dark">María Gómez</div>
                                    <small class="text-muted">maria.g@test.com</small>
                                </div>
                            </div>
                        </td>
                        <td>33445566 SC</td>
                        <td>+591 66554433</td>
                        <td><span class="badge bg-secondary">1 Registrado</span></td>
                        <td>
                            <button class="btn btn-sm btn-light me-1"><i class="bi bi-pencil"></i></button>
                            <button class="btn btn-sm btn-light text-danger"><i class="bi bi-trash"></i></button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <div class="d-flex justify-content-between align-items-center mt-3">
            <span class="text-muted small">Mostrando 2 de 42 clientes</span>
            <nav>
                <ul class="pagination pagination-sm mb-0">
                    <li class="page-item disabled"><a class="page-link" href="#">Anterior</a></li>
                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item"><a class="page-link" href="#">Siguiente</a></li>
                </ul>
            </nav>
        </div>
    </div>
</div>
@endsection
