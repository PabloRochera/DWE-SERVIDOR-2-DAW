<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Archivos</title>
    <style>
        /* General styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
            color: #333;
        }

        /* Header styles */
        header {
            background-color: #007bff;
            color: #fff;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        header h1 {
            font-size: 1.5rem;
            margin: 0;
        }

        .profile-icon {
            display: flex;
            align-items: center;
        }

        .profile-icon a {
            text-decoration: none;
            color: white;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
        }

        .profile-icon svg {
            margin-right: 5px;
            fill: white;
            width: 18px;
            height: 18px;
        }

        /* Container styles */
        .container {
            max-width: 900px;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        /* Form styles */
        form {
            margin-bottom: 20px;
            display: flex;
            align-items: center;
        }

        form input[type="text"],
        form input[type="file"] {
            flex: 1;
            padding: 10px;
            margin-right: 10px;
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
            font-size: 0.9rem;
        }

        form button:hover {
            background-color: #0056b3;
        }

        /* Table styles */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table th, table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
            font-size: 0.9rem;
        }

        table th {
            background-color: #007bff;
            color: white;
        }

        table tr:hover {
            background-color: #f1f1f1;
        }

        /* Actions styles */
        .actions a, .actions form button {
            margin-right: 8px;
            padding: 5px 10px;
            font-size: 0.8rem;
            color: #007bff;
            text-decoration: none;
            border: 1px solid #007bff;
            border-radius: 4px;
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
            border: 1px solid #007bff;
            padding: 5px 10px;
            cursor: pointer;
        }

        .no-files {
            text-align: center;
            padding: 20px;
            color: #666;
        }
    </style>
</head>
<body>
    <header>
        <!-- Icono de perfil -->
        <div class="profile-icon">
            <a href="{{ route('profile.edit') }}">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                    <path d="M12 12c2.7 0 4.875-2.175 4.875-4.875S14.7 2.25 12 2.25 7.125 4.425 7.125 7.125 9.3 12 12 12zm0 1.5c-3.225 0-9.75 1.725-9.75 5.25v2.25h19.5v-2.25c0-3.525-6.525-5.25-9.75-5.25z"></path>
                </svg>
                Perfil
            </a>
        </div>
        <h1>Gestión de Archivos</h1>
    </header>

    <div class="container">
        <!-- Formulario de Búsqueda -->
        <form action="{{ route('files.search') }}" method="GET">
            <input type="text" name="query" placeholder="Buscar archivos..." required>
            <button type="submit">Buscar</button>
        </form>

        <!-- Formulario de Subida -->
        <form action="{{ route('files.upload') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="file" name="file" required>
            <button type="submit">Subir Archivo</button>
        </form>

        <!-- Tabla de Archivos -->
        <h2>Archivos</h2>
        <table>
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($files as $file)
                    <tr>
                        <td>{{ $file->name }}</td>
                        <td class="actions">
                            <a href="{{ route('files.preview', $file->id) }}">Vista Previa</a>
                            <a href="{{ route('files.versions', $file->id) }}">Versiones</a>
                            @if (!$file->trashed())
                                <a href="{{ route('files.download', $file->id) }}">Descargar</a>
                                <form action="{{ route('files.delete', $file->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit">Borrar</button>
                                </form>
                            @else
                                <form action="{{ route('files.restore', $file->id) }}" method="POST">
                                    @csrf
                                    <button type="submit">Restaurar</button>
                                </form>
                            @endif
                            <form action="{{ route('files.forceDelete', $file->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit">Eliminar Permanente</button>
                            </form>
                            <form action="{{ route('files.share', $file->id) }}" method="GET">
                                <button type="submit">Compartir</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2" class="no-files">No se encontraron archivos</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</body>
</html>
