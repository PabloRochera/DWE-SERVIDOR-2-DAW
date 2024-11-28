<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Archivos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
        }

        header {
            background-color: #007bff;
            color: #fff;
            padding: 20px;
            text-align: center;
        }

        .container {
            max-width: 1200px;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            border-radius: 8px;
        }

        .messages {
            margin-bottom: 20px;
            padding: 15px;
            border-radius: 5px;
        }

        .messages.success {
            background-color: #d4edda;
            color: #155724;
        }

        .messages.error {
            background-color: #f8d7da;
            color: #721c24;
        }

        form {
            margin-bottom: 20px;
        }

        form input[type="text"],
        form input[type="file"] {
            padding: 10px;
            margin-right: 10px;
            width: calc(100% - 120px);
            max-width: 400px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        form button {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        form button:hover {
            background-color: #0056b3;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th, table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        table th {
            background-color: #007bff;
            color: white;
        }

        table tr:nth-child(even) {
            background-color: #f4f4f9;
        }

        table tr:hover {
            background-color: #f1f1f1;
        }

        .actions a, .actions form button {
            margin-right: 10px;
            padding: 5px 10px;
            font-size: 14px;
            color: #007bff;
            text-decoration: none;
            border: 1px solid #007bff;
            border-radius: 5px;
        }

        .actions a:hover, .actions form button:hover {
            background-color: #007bff;
            color: white;
        }

        .actions form {
            display: inline-block;
        }

        .actions form button {
            background: none;
            color: #007bff;
            border: 1px solid #007bff;
            padding: 5px 10px;
            cursor: pointer;
        }

        .actions form button:hover {
            background-color: #007bff;
            color: white;
        }

        .no-files {
            text-align: center;
            padding: 20px;
            color: #666;
        }
    </style>
</head>
<body>
    <h1>Gestión de Archivos</h1>

    <!-- Mostrar mensajes -->
    @if (session('success'))
        <p style="color: green;">{{ session('success') }}</p>
    @endif
    @if (session('error'))
        <p style="color: red;">{{ session('error') }}</p>
    @endif

    <!-- Formulario de Búsqueda -->
    <form action="{{ route('files.search') }}" method="GET" style="margin-bottom: 20px;">
        <input type="text" name="query" placeholder="Buscar archivos..." required>
        <button type="submit">Buscar</button>
    </form>

    <!-- Formulario de Subida -->
    <form action="{{ route('files.upload') }}" method="POST" enctype="multipart/form-data" style="margin-bottom: 20px;">
        @csrf
        <input type="file" name="file" required>
        <button type="submit">Subir Archivo</button>
    </form>

    <h2>Archivos</h2>
    <table border="1" style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Acciones</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($files as $file)
                <tr>
                    <td>{{ $file->name }}</td>
                    <td>
                        <a href="{{ route('files.preview', $file->id) }}">Vista Previa</a>
                        <a href="{{ route('files.versions', $file->id) }}">Versiones</a>
                        @if (!$file->trashed())
                            <a href="{{ route('files.download', $file->id) }}">Descargar</a>
                            <form action="{{ route('files.delete', $file->id) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit">Borrar</button>
                            </form>
                        @else
                            <form action="{{ route('files.restore', $file->id) }}" method="POST" style="display: inline;">
                                @csrf
                                <button type="submit">Restaurar</button>
                            </form>
                        @endif
                        <form action="{{ route('files.forceDelete', $file->id) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit">Eliminar Permanentemente</button>
                        </form>
                        <form action="{{ route('files.share', $file->id) }}" method="GET" style="display: inline;">
                            @csrf
                            <button type="submit">Compartir</button>
                        </form>
                    </td>
                    <td>{{ $file->trashed() ? 'Eliminado' : 'Activo' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" style="text-align: center;">No se encontraron archivos</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
