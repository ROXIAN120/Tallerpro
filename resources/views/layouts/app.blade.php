<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Taller Automotriz</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f5f8fa;
            overflow-x: hidden;
        }
        .sidebar {
            height: 100vh;
            background-color: #1e1e2d; /* Dark theme */
            color: #a2a3b7;
            position: fixed;
            width: 260px;
            z-index: 1000;
            transition: all 0.3s;
        }
        .sidebar .logo {
            background-color: #1b1b28;
            padding: 20px;
            font-size: 1.5rem;
            font-weight: 700;
            color: #ffffff;
            text-align: center;
            border-bottom: 1px solid #2b2b40;
        }
        .sidebar .nav-link {
            color: #a2a3b7;
            padding: 12px 20px;
            border-radius: 8px;
            margin: 5px 15px;
            font-weight: 500;
            transition: all 0.3s;
        }
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            background-color: #009ef7; /* Striking Blue Accent */
            color: #ffffff;
        }
        .sidebar .nav-link i {
            margin-right: 10px;
            font-size: 1.2rem;
        }
        .main-content {
            margin-left: 260px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .navbar {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
            padding: 15px 30px;
        }
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.03);
            transition: transform 0.2s;
        }
        .card:hover {
            transform: translateY(-5px);
        }
        .btn-primary {
            background-color: #009ef7;
            border-color: #009ef7;
            border-radius: 8px;
        }
        .btn-primary:hover {
            background-color: #008be1;
            border-color: #008be1;
        }
        .table {
            vertical-align: middle;
        }
        .table thead th {
            color: #b5b5c3;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85rem;
            border-bottom-width: 1px;
            background-color: transparent;
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <nav class="sidebar d-flex flex-column">
        <div class="logo">
            <i class="bi bi-tools text-primary"></i> TallerApp
        </div>
        <ul class="nav flex-column mt-4">
            <li class="nav-item">
                <a class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}" href="/dashboard">
                    <i class="bi bi-grid-1x2"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('ordenes*') ? 'active' : '' }}" href="#">
                    <i class="bi bi-clipboard2-data"></i> Órdenes de Trabajo
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('clientes*') ? 'active' : '' }}" href="/clientes">
                    <i class="bi bi-people"></i> Clientes
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">
                    <i class="bi bi-car-front"></i> Vehículos
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/servicios">
                    <i class="bi bi-wrench-adjustable"></i> Servicios
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">
                    <i class="bi bi-box-seam"></i> Inventario & Repuestos
                </a>
            </li>
            <li class="nav-item mt-5">
                <form action="/logout" method="POST" class="px-3">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger w-100 rounded-3">
                        <i class="bi bi-box-arrow-right"></i> Cerrar Sesión
                    </button>
                </form>
            </li>
        </ul>
    </nav>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Topbar -->
        <nav class="navbar navbar-expand-lg mb-4">
            <div class="container-fluid">
                <h4 class="mb-0 fw-bold text-dark">@yield('title', 'Dashboard')</h4>
                <div class="d-flex align-items-center">
                    <div class="dropdown">
                        <button class="btn btn-light dropdown-toggle rounded-pill px-4" type="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle text-primary"></i> 
                            {{ Auth::user()->name ?? 'Administrador' }}
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="#">Mi Perfil</a></li>
                            <li><a class="dropdown-item" href="#">Configuración</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <div class="container-fluid px-4 pb-4">
            @yield('content')
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
