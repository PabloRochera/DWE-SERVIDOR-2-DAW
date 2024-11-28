<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\File;
use Illuminate\Support\Str;

class FileController extends Controller
{
    // Listar todos los archivos
    public function index()
    {
        $files = File::withTrashed()->get(); // Incluye los eliminados
        return view('files.index', compact('files'));
    }

    // Subir un archivo
    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:2048',
        ]);

        $path = $request->file('file')->store('uploads', 'public');

        $file = File::create([
            'name' => $request->file('file')->getClientOriginalName(),
            'path' => $path,
        ]);

        return redirect()->back()->with('success', 'Archivo subido exitosamente.');
    }

    // Descargar un archivo
    public function download($id)
    {
        $file = File::findOrFail($id);

        $filePath = storage_path('app/public/' . $file->path);
        if (file_exists($filePath)) {
            return response()->download($filePath, $file->name);
        }

        return redirect()->back()->with('error', 'Archivo no encontrado.');
    }

    // Borrar (Soft Delete) un archivo
    public function delete($id)
    {
        $file = File::findOrFail($id);
        $file->delete();

        return redirect()->back()->with('success', 'Archivo borrado exitosamente.');
    }

    // Restaurar un archivo
    public function restore($id)
    {
        $file = File::withTrashed()->findOrFail($id);
        $file->restore();

        return redirect()->back()->with('success', 'Archivo restaurado exitosamente.');
    }

    // Eliminar permanentemente un archivo
    public function forceDelete($id)
    {
        $file = File::withTrashed()->findOrFail($id);

        if (Storage::disk('public')->exists($file->path)) {
            Storage::disk('public')->delete($file->path);
        }

        $file->forceDelete();

        return redirect()->back()->with('success', 'Archivo eliminado permanentemente.');
    }

    // Compartir un archivo
    public function share($id)
    {
        $file = File::findOrFail($id);

        $file->share_token = Str::random(40);
        $file->save();

        $shareableLink = route('files.shared', ['token' => $file->share_token]);

        return redirect()->back()->with('success', 'Enlace de compartición generado: ' . $shareableLink);
    }

    // Descargar archivo compartido
    public function shared($token)
    {
        $file = File::where('share_token', $token)->firstOrFail();

        $filePath = storage_path('app/public/' . $file->path);
        if (file_exists($filePath)) {
            return response()->download($filePath, $file->name);
        }

        return redirect()->back()->with('error', 'Archivo no encontrado.');
    }

    // Vista previa del archivo
    public function preview($id)
    {
        $file = File::findOrFail($id);

        if (Storage::disk('public')->exists($file->path)) {
            $content = Storage::disk('public')->get($file->path);

            return view('files.preview', compact('file', 'content'));
        }

        return redirect()->back()->with('error', 'Archivo no encontrado.');
    }

    // Guardar cambios en el archivo
    public function updateFile(Request $request, $id)
    {
        $file = File::findOrFail($id);

        $request->validate([
            'content' => 'required',
        ]);

        $versionedPath = 'uploads/versions/' . now()->timestamp . '-' . $file->name;
        Storage::disk('public')->copy($file->path, $versionedPath);

        Storage::disk('public')->put($file->path, $request->content);

        return redirect()->back()->with('success', 'Archivo actualizado exitosamente.');
    }

    // Listar versiones anteriores
    public function versions($id)
    {
        $file = File::findOrFail($id);

        $versions = Storage::disk('public')->files('uploads/versions');
        $filteredVersions = array_filter($versions, function ($version) use ($file) {
            return Str::contains($version, $file->name);
        });

        return view('files.versions', compact('file', 'filteredVersions'));
    }

    // Búsqueda de archivos
    public function search(Request $request)
    {
        $query = $request->input('query');

        $files = File::withTrashed()
            ->where('name', 'LIKE', '%' . $query . '%')
            ->get();

        return view('files.index', compact('files'));
    }
}
