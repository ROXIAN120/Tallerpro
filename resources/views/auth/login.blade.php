<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Taller Automotriz</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #1e1e2d; /* Dark background matching the new theme */
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
            overflow: hidden;
            width: 100%;
            max-width: 400px;
        }
        .login-header {
            background-color: #009ef7; /* Striking Blue Accent */
            color: #ffffff;
            padding: 30px 20px;
            text-align: center;
        }
        .login-header h3 {
            font-weight: 700;
            margin: 0;
            letter-spacing: 1px;
        }
        .login-body {
            padding: 40px 30px;
        }
        .form-control {
            padding: 12px 15px;
            border-radius: 8px;
            background-color: #f5f8fa;
            border: 1px solid #e4e6ef;
        }
        .form-control:focus {
            background-color: #ffffff;
            border-color: #009ef7;
            box-shadow: 0 0 0 0.25rem rgba(0, 158, 247, 0.25);
        }
        .btn-primary {
            background-color: #009ef7;
            border-color: #009ef7;
            padding: 12px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s;
        }
        .btn-primary:hover {
            background-color: #008be1;
            transform: translateY(-2px);
        }
    </style>
</head>
<body>

    <div class="login-card">
        <div class="login-header">
            <h3>Taller Automotriz</h3>
            <p class="mb-0 mt-2 opacity-75 small">Gestión Integral</p>
        </div>
        <div class="login-body">
            @if ($errors->any())
                <div class="alert alert-danger p-2 mb-4 rounded-3 small">
                    <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="/" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="email" class="form-label fw-semibold text-dark">Correo Electrónico</label>
                    <input type="email" name="email" id="email" class="form-control" placeholder="admin@admin.com" value="{{ old('email') }}" required autofocus>
                </div>
                <div class="mb-4">
                    <label for="password" class="form-label fw-semibold text-dark">Contraseña</label>
                    <input type="password" name="password" id="password" class="form-control" placeholder="••••••••" required>
                </div>
                
                <button type="submit" class="btn btn-primary w-100 mt-2">
                    Iniciar Sesión
                </button>
            </form>
        </div>
    </div>

</body>
</html>
