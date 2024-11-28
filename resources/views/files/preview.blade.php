<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Vista Previa</title>
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
            max-width: 800px;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            border-radius: 8px;
        }

        textarea {
            width: 100%;
            height: 400px;
            font-size: 14px;
            font-family: monospace;
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 10px;
            resize: vertical;
        }

        button {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
        }

        button:hover {
            background-color: #0056b3;
        }
    </style>
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
