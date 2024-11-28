<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Vista Previa</title>
</head>
<body>
    <h1>Vista Previa del Archivo</h1>
    <form action="{{ route('files.update', $file->id) }}" method="POST">
        @csrf
        <textarea name="content" rows="20" cols="80">{{ $content }}</textarea>
        <br>
        <button type="submit">Guardar Cambios</button>
    </form>
</body>
</html>
