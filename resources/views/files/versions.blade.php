<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Versiones</title>
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

        ul {
            list-style-type: none;
            padding: 0;
        }

        ul li {
            background-color: #f9f9f9;
            margin: 10px 0;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        ul li a {
            color: #007bff;
            text-decoration: none;
            font-weight: bold;
        }

        ul li a:hover {
            text-decoration: underline;
        }
    </style>
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
