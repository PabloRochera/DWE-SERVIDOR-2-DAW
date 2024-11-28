<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido</title>
    <!-- Fuente y estilos simples -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            font-family: 'Inter', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: #f9f9f9;
        }

        .container {
            text-align: center;
            background: #fff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
        }

        .container h1 {
            font-size: 24px;
            color: #333;
            margin-bottom: 20px;
            font-weight: 600;
        }

        .container p {
            font-size: 14px;
            color: #666;
            margin-bottom: 30px;
        }

        .buttons a {
            display: block;
            width: 100%;
            margin: 10px 0;
            padding: 12px;
            font-size: 16px;
            font-weight: 600;
            color: #fff;
            background-color: #007bff;
            border: none;
            border-radius: 8px;
            text-decoration: none;
            text-align: center;
            transition: background-color 0.3s ease;
        }

        .buttons a:hover {
            background-color: #0056b3;
        }

        .buttons a.register {
            background-color: #28a745;
        }

        .buttons a.register:hover {
            background-color: #1e7e34;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Bienvenido</h1>
        <p>Accede o regístrate para gestionar tus archivos.</p>
        <div class="buttons">
            <a href="{{ route('login') }}">Iniciar Sesión</a>
            <a href="{{ route('register') }}" class="register">Registrarse</a>
        </div>
    </div>
</body>
</html>
