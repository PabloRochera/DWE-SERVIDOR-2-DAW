<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Versiones</title>
</head>
<body>
    <h1>Versiones del Archivo: {{ $file->name }}</h1>
    <ul>
        @foreach ($filteredVersions as $version)
            <li>
                <a href="{{ asset('storage/' . $version) }}" target="_blank">
                    Descargar: {{ basename($version) }}
                </a>
            </li>
        @endforeach
    </ul>
</body>
</html>
