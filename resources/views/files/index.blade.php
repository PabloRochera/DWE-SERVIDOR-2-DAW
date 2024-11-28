<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Archivos</title>
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
